<?php

namespace App\Jobs;

use App\Models\Paper;
use App\Models\User;
use App\Services\WikipediaKeywordScraper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use StopWordFactory;
use TextAnalysis\Collections\DocumentArrayCollection;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Filters\CharFilter;
use TextAnalysis\Filters\LambdaFilter;
use TextAnalysis\Filters\LowerCaseFilter;
use TextAnalysis\Filters\NumbersFilter;
use TextAnalysis\Filters\PunctuationFilter;
use TextAnalysis\Filters\QuotesFilter;
use TextAnalysis\Filters\StopWordsFilter;
use TextAnalysis\Indexes\TfIdf;
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
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        ini_set('max_execution_time', 300);
        $this->tokenizer = new GeneralTokenizer();
        $this->scraper = new WikipediaKeywordScraper();
        $this->filters = [
            new LowerCaseFilter(),
            new QuotesFilter(),
            new CharFilter(),
            new NumbersFilter(),
            new PunctuationFilter(),
            new StopWordsFilter(StopWordFactory::get('stop-words_english_6_en.txt'))
        ];
        $this->stemmer = new PorterStemmer();
    }


    public function transformDocument(TokensDocument $document)
    {
        foreach ($this->filters as $filter) {
            $document->applyTransformation($filter);
        }
        $document->applyStemmer($this->stemmer);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $domainsByProffessor = User::professor()->with('professorDetails')->get()->mapWithKeys(function (User $professor) {
            return [$professor->email => collect(explode(', ', $professor->professorDetails->interest_domains))];
        });
        $documentsToScrape = $domainsByProffessor->flatten()->filter()->unique();
        $documents = $documentsToScrape->mapWithKeys(function ($domain) {
            return [$domain => $this->scraper->getText($domain)->transform(function ($text) {
                return new TokensDocument($this->tokenizer->tokenize($text));
            })];

        });

        $collection = new DocumentArrayCollection($documents->flatten()->all());

        /** @noinspection PhpParamsInspection */
        $charFilter = new LambdaFilter(function ($word) {
            return strlen($word) == 1 ? null : $word;
        });

        /** @noinspection PhpParamsInspection */
        $wordsFilter = new LambdaFilter(function ($word) {
            return preg_replace('/\W+/', '', $word);
        });

        $transformations = [
            new LowerCaseFilter(),
            $wordsFilter,
            new StopWordsFilter(StopWordFactory::get('stop-words_english_6_en.txt')),
        ];
        $stemmers = [
            new PorterStemmer(),
        ];
        $collection->applyTransformations($transformations);
        $collection->applyStemmers($stemmers);

        $tfIdf = new TfIdf($collection);

        $professorKeywords = $domainsByProffessor->map(function (Collection $domains) use ($documents, $tfIdf) {
            return $domains->filter()
                ->map(function ($domain) use ($documents) {
                    return $documents[$domain];
                })
                ->flatten()
                ->transform(function (TokensDocument $document) {
                    return freq_dist($document->getDocumentData())->getKeyValuesByWeight();

                })
                ->transform(function ($freq) {
                    return collect($freq)->transform(function ($score, $keyword) {
                        return [$keyword, $score];
                    })->values();
                })
                ->flatten(1)->mapToGroups(function ($item) {
                    return [$item[0] => $item[1]];
                })
                ->transform(function ($scores) {
                    return $scores->sum();
                })
                ->transform(function ($score, $keyword) use ($tfIdf) {
                    return $score * $tfIdf->getIdf($keyword);
                })
                ->sortByDesc(function ($item) {
                    return $item;
                });
        });

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

        $paperDocs = Paper::get()->keyBy('name')->transform(function (Paper $paper) use ($parser) {
            return $parser->parseContent(Storage::get($paper->filepath))->getText();
        })->transform(function ($text, $name) {
            return new TokensDocument($this->tokenizer->tokenize($text), $name);
        });

        $paperDocCollection = new DocumentArrayCollection($paperDocs->all());
        $paperDocCollection->applyTransformations($transformations);
        $paperDocCollection->applyStemmers($stemmers);

        $papersTfIdf = new TfIdf($paperDocCollection);

        $paperDocs->map(function (TokensDocument $paperDoc) use ($papersTfIdf) {
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
                })->take(5);
            });
        })->map(function (Collection $profs) {
            return $profs->sortByDesc(function (Collection $scores) {
                return $scores->sum(function ($item) {
                    return $item[3];
                });
            })->map(function ($a, $b) {
                return [$a, $b];
            })->take(4);
        })->dd();
    }
}
