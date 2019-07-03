<?php

namespace App\Jobs;

use App\Models\PaperRevision;
use App\Models\User;
use App\Services\TFIDF;
use App\Services\WikipediaKeywordScraper;
use DonatelloZa\RakePlus\RakePlus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Log;
use StopWordFactory;
use TextAnalysis\Collections\DocumentArrayCollection;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Filters\LambdaFilter;
use TextAnalysis\Filters\LowerCaseFilter;
use TextAnalysis\Filters\StopWordsFilter;
use TextAnalysis\Filters\TrimFilter;
use TextAnalysis\Stemmers\PorterStemmer;
use TextAnalysis\Tokenizers\GeneralTokenizer;

class EvaluatorKeywordExtractor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var WikipediaKeywordScraper
     */
    private $scraper;
    /**
     * @var array
     */
    private $filters;
    /**
     * @var PorterStemmer
     */
    private $stemmer;
    /**
     * @var GeneralTokenizer
     */
    private $tokenizer;


    /**
     * @var Collection
     */
    private $paperDocuments;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tokenizer = new GeneralTokenizer();
        $this->scraper = new WikipediaKeywordScraper();

        /** @noinspection PhpParamsInspection */
        $charFilter = new LambdaFilter(function ($word) {
            return strlen($word) == 1 ? null : $word;
        });

        /** @noinspection PhpParamsInspection */
        $wordsFilter = new LambdaFilter(function ($word) {
            return preg_replace('/[^a-z]/', '', $word);
        });

        $stopWords = StopWordFactory::get('stop-words_english_6_en.txt');
        $this->filters = [
            new LowerCaseFilter(),
            $wordsFilter,
            $charFilter,
            new StopWordsFilter($stopWords),
        ];

        $this->stemmer = new PorterStemmer();

        $this->paperDocuments = collect();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $domainDocuments = [];

        $domainsByProfessor = User::professor()->with('professorDetails')->get()->mapWithKeys(function (User $professor) {
            return [$professor->email => collect(explode(', ', $professor->professorDetails->interest_domains))];
        });
        $rake = new RakePlus();
        foreach ($domainsByProfessor->flatten()->filter()->unique() as $domainToScrape) {
            $domainDocuments[$domainToScrape] = [];
            foreach ($this->scraper->getTexts($domainToScrape) as $text) {
                //                $domainDocuments[$domainToScrape][] = new TokensDocument(ngrams($this->transformDocument(new TokensDocument($this->tokenizer->tokenize($text)))->getDocumentData(), 3, ' '));
                //                $domainDocuments[$domainToScrape][] = $this->transformDocument(new TokensDocument($this->tokenizer->tokenize($text)));
                $domainDocuments[$domainToScrape][] = $rake->extract($text)->scores();
            }
        }

        $tfIdf = (new TFIDF(Arr::flatten($domainDocuments, 1)))->getIdf();
        $profKeywords = [];
        foreach ($domainsByProfessor as $professor => $domains) {
            $profKeywords[$professor] = [];
            foreach ($domains as $domain) {
                foreach ($domainDocuments[$domain] as $document) {

                    //                    $freqs = freq_dist($document)->getKeyValuesByFrequency();
                    foreach ($document as $word => $freq) {
                        if ($tfIdf[$word] == 0) continue;
                        if (!isset($profKeywords[$professor][$word])) {
                            $profKeywords[$professor][$word] = 0;
                        }
                        $profKeywords[$professor][$word] += $freq / count($document) * $tfIdf[$word];
                    }
                }
            }
            arsort($profKeywords[$professor]);
        }


        dd($profKeywords);
        //        dd(array_map(function($arr) {
        //            return array_sum($arr);
        //        }, $profKeywords));

        return;
        $professorKeywords->transform(function (Collection $scores, $prof) {
            if ($scores->first() == 0) {
                return $scores;
            }

            $factor = 1 / $scores->first();

            return $scores->transform(function ($score) use ($factor) {
                return $score * $factor;
            });
        });

        $parser = new Parser();

        $paperDocs = PaperRevision::get()->keyBy('name')->transform(function (PaperRevision $paper, $name) use ($parser, $transformations, $stemmer) {
            $before = memory_get_usage();
            $tokensDocument = new TokensDocument($this->tokenizer->tokenize($parser->parseContent(Storage::get($paper->filepath))->getText()), $name);
            foreach ($transformations as $transformation) {
                $tokensDocument->applyTransformation($transformation);
            }

            $tokensDocument->applyStemmer($stemmer);


            Log::info(memory_get_usage() - $before);
            return $tokensDocument;
        });

        $paperDocCollection = new DocumentArrayCollection($paperDocs->all());

        $papersTfIdf = new TFIDF($paperDocCollection);
        $paperDocs->transform(function (TokensDocument $paperDoc) use ($papersTfIdf) {
            return collect(freq_dist($paperDoc->getDocumentData())->getKeyValuesByWeight())->transform(function ($score, $keyword) use ($papersTfIdf) {
                return $score * $papersTfIdf->getIdf($keyword);
            });
        })->transform(function (Collection $scores) {
            $max = $scores->max();
            if ($max == 0) {
                return $scores;
            }

            $factor = 1 / $max;
            return $scores->transform(function ($score) use ($factor) {
                return $score * $factor;
            });
        })->map(function (Collection $scores, $paper) use ($professorKeywords) {
            return $professorKeywords->map(function (Collection $keywords, $prof) use ($scores) {
                return $scores->intersectByKeys($keywords)->keys()->map(function ($keyword) use ($scores, $keywords) {
                    return [$keyword, $keywords[$keyword], $scores[$keyword], $keywords[$keyword] * $scores[$keyword]];
                })->sortByDesc(function ($item) {
                    return $item[3];
                });
            });
        })->map(function (Collection $profs) {
            //            return $profs->map(function ($scores) {
            //                return $scores->sum(function ($item) {
            //                    return $item[3];
            //                });
            //            })->sortByDesc(function ($item) {
            //                return $item;
            //            });

            return $profs->sortByDesc(function (Collection $scores) {
                return $scores->sum(function ($item) {
                    return $item[3];
                });
            })->take(5)->map(function ($a, $b) {
                return [$a, $b];
            });
        })->take(1)->dd();
    }

    public function transformDocument(TokensDocument $document)
    {
        foreach ($this->filters as $filter) {
            $document->applyTransformation($filter);
        }
        $document->applyStemmer($this->stemmer);
        return $document;
    }
}
