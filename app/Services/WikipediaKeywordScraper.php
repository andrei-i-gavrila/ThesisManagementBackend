<?php


namespace App\Services;


use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use StopWordFactory;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Filters\StopWordsFilter;
use TextAnalysis\Tokenizers\GeneralTokenizer;

class WikipediaKeywordScraper
{


    /**
     * @var Client
     */
    private $client;
    /**
     * @var GeneralTokenizer
     */
    private $tokenizer;
    /**
     * @var StopWordsFilter
     */
    private $filter;

    public function __construct()
    {
        $this->client = new Client(['verify' => false]);
        $this->tokenizer = new GeneralTokenizer();
        $this->filter = new StopWordsFilter(StopWordFactory::get("stop-words_english_6_en.txt"));
    }

    public function getText($query)
    {
        $title = $this->getPageTitle($query);

        if ($title) {

            $textPage = $this->getTextPage($title);
            if (strlen($textPage) > 250) {
                return collect([$textPage]);
            } else {
                \Log::info("skipped short page");
            }
        }

        return $this->getQueriesToTry($query)
                        ->transform(function ($query) {
                            return $this->getPageTitle($query);
                        })
                        ->filter()
                        ->transform(function ($title) {
                            return $this->getTextPage($title);
                        })
                        ->filter(function ($text) {
                            return strlen($text) > 250 && !Str::contains($text, "may refer to:\n");
                        });
    }

    private function getPageTitle($query)
    {
        $opensearchUrl = $this->getOpensearchUrl($query);

        $contents = Cache::rememberForever($opensearchUrl, function () use ($opensearchUrl) {
            return $this->client->get($opensearchUrl)->getBody()->getContents();
        });

        return json_decode($contents)[1][0] ?? null;
    }

    private function getOpensearchUrl($query)
    {
        $query = urlencode($query);
        return "https://en.wikipedia.org/w/api.php?action=opensearch&format=json&search=$query";
    }

    private function getTextPage($title)
    {
        $textUrl = $this->getTextUrl($title);

        $contents = Cache::rememberForever($textUrl, function () use ($textUrl) {
            return $this->client->get($textUrl)->getBody()->getContents();
        });

        $jsonAnswer = json_decode($contents, true);
        unset($contents);
        try {
            return array_values($jsonAnswer['query']['pages'])[0]['extract'];
        } catch (Exception $e) {
            dd($jsonAnswer, $title);
            return "";
        }
    }

    private function getTextUrl($title)
    {
        $title = urlencode($title);
        return "https://en.wikipedia.org/w/api.php?action=query&format=json&prop=extracts&redirects=1&explaintext=1&exsectionformat=plain&titles=$title";
    }

    private function getQueriesToTry($query)
    {
        $tokens = (new TokensDocument(normalize_tokens($this->tokenizer->tokenize($query))))->applyTransformation($this->filter);
        return collect(array_merge(ngrams($tokens->getDocumentData()), $tokens->getDocumentData()));
    }
}