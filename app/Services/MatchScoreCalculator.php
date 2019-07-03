<?php


namespace App\Services;


use App\Models\Committee;
use App\Models\DomainOfInterest;
use App\Models\ExamSession;
use App\Models\Paper;
use App\Models\PaperProfessorScore;
use App\Models\PaperRevision;
use App\Models\User;
use Illuminate\Support\Collection;

class MatchScoreCalculator
{

    /**
     * @var WikipediaKeywordScraper
     */
    private $wikiScraper;
    /**
     * @var KeywordExtractorService
     */
    private $keywordExtractorService;

    public function __construct(WikipediaKeywordScraper $wikiScraper, KeywordExtractorService $keywordExtractorService)
    {

        $this->wikiScraper = $wikiScraper;
        $this->keywordExtractorService = $keywordExtractorService;
    }

    public function calculate(ExamSession $examSession)
    {
        $tfidfs = ['ro' => new TFIDF(), 'en' => new TFIDF()];


        $committees = $this->loadProfessorsInCommittees($examSession);


        $tfProfessors = $committees->mapWithKeys(function (User $user) use ($tfidfs) {
            return [$user->id => [
                'ro' => $this->processProfessor($user, $tfidfs, 'ro'),
                'en' => $this->processProfessor($user, $tfidfs, 'en')
            ]];
        });

        $papers = $this->loadStudents($examSession);

        $paperScores = $papers->mapWithKeys(function (Paper $paper) use ($tfidfs) {
            $freqs = $this->loadKeywordsFromTxtFile($paper->finalRevision);
            $tfidfs[$paper->language]->indexDocument($freqs);
            return [$paper->id => ['scores' => $freqs, 'lang' => $paper->language]];
        });

        $tfidfs['ro']->calculateIdf();
        $tfidfs['en']->calculateIdf();


        $tfProfessors->transform(function ($professorData) use ($tfidfs) {
            return [
                'ro' => $professorData['ro']->map(function ($scores) use ($tfidfs) {
                    return $tfidfs['ro']->getTfIdfs($scores);
                }),
                'en' => $professorData['en']->map(function ($scores) use ($tfidfs) {
                    return $tfidfs['en']->getTfIdfs($scores);
                })
            ];
        });

        $paperScores->transform(function ($paperData) use ($tfidfs) {
            return $tfidfs[$paperData['lang']]->getTfIdfs($paperData['scores']);
        });

        $papers->each(function (Paper $paper) use ($tfProfessors, $paperScores) {
            $tfProfessors->each(function($professor, $profId) use ($paperScores, $paper) {
                $matchScore = $professor[$paper->language]->map(function($scores) use ($paperScores, $paper) {
                    return $this->cosineSimilarity($scores, $paperScores[$paper->id]);
                })->sum();

                PaperProfessorScore::updateOrCreate(['paper_id' => $paper->id, 'professor_id' => $profId], ['value' => $matchScore]);
            });
        });

    }

    private function cosineSimilarity($scoresA, $scoresB)
    {
        $sumProd = 0;
        $aSq = 0;
        $bSq = 0;

        foreach ($scoresA as $word => $score) {
            if (isset($scoresB[$word])) {
                $sumProd += $score * $scoresB[$word];
            }
            $aSq += $score * $score;
        }

        foreach ($scoresB as $word => $score) {
            $bSq += $score * $score;
        }


        return $sumProd / sqrt($aSq * $bSq);
    }

    private function loadProfessorsInCommittees(ExamSession $examSession): Collection
    {
        return User::find($examSession->committees->flatMap(function (Committee $committee) {
            return [$committee->member1_id, $committee->leader_id, $committee->member2_id];
        }))->load('domainsOfInterest');
    }

    private function processProfessor(User $user, $tfIdf, $language)
    {
        return $user->domainsOfInterest->filter(function (DomainOfInterest $domainOfInterest) use ($language) {
            return $domainOfInterest->language === $language;
        })->flatMap(function (DomainOfInterest $domainOfInterest) use ($language) {
            return $this->wikiScraper->getTexts($domainOfInterest->name, $language);
        })->transform(function ($text) use ($tfIdf, $language) {
            $freqDist = freq_dist($this->keywordExtractorService->extract($text, $language))->getKeyValuesByFrequency();
            $tfIdf[$language]->indexDocument($freqDist);
            return $freqDist;
        });
    }

    private function loadStudents(ExamSession $examSession): Collection
    {
        return $examSession->papers->load('finalRevision');
    }

    private function loadKeywordsFromTxtFile(PaperRevision $paperRevision)
    {
        return freq_dist(explode("\n", \Storage::get($paperRevision->filepath . '.keywords')))->getKeyValuesByFrequency();
    }

}