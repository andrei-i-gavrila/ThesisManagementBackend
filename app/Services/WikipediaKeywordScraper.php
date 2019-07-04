<?php


namespace App\Services;


use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Log;

class WikipediaKeywordScraper
{


    /**
     * @var Client
     */
    private $client;
    /**
     * @var KeywordExtractorService
     */
    private $keywordExtractorService;

    public function __construct(KeywordExtractorService $keywordExtractorService)
    {
        $this->client = new Client(['verify' => false]);
        $this->keywordExtractorService = $keywordExtractorService;
    }

    public function getTexts($query, $language = 'en')
    {
        if (empty($query)) {
            \Log::info('wtf ');
            return collect();
        }
        $title = $this->getPageTitle($query, $language);

        if ($title) {

            $textPage = $this->getTextPage($title, $language);
            if (strlen($textPage) > 250) {
                return collect([$textPage]);
            } else {
                Log::info("skipped short page");
            }
        }

        return $this->getQueriesToTry($query, $language)
            ->transform(function ($query) use ($language) {
                return $this->getPageTitle($query, $language);
            })
            ->filter()
            ->transform(function ($title) use ($language) {
                return $this->getTextPage($title, $language);
            })
            ->filter(function ($text) {
                return strlen($text) > 250 && !Str::contains($text, "may refer to:\n");
            });
    }

    private function getPageTitle($query, $language)
    {
        $opensearchUrl = $this->getOpensearchUrl($query, $language);

        $contents = Cache::rememberForever($opensearchUrl, function () use ($opensearchUrl) {
            return $this->client->get($opensearchUrl)->getBody()->getContents();
        });
        return json_decode($contents)[1][0] ?? null;
    }

    private function getOpensearchUrl($query, $language)
    {
        $query = urlencode($query);
        return "https://$language.wikipedia.org/w/api.php?action=opensearch&format=json&search=$query";
    }

    private function getTextPage($title, $language)
    {
        $textUrl = $this->getTextUrl($title, $language);

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

    private function getTextUrl($title, $language)
    {
        $title = urlencode($title);
        return "https://$language.wikipedia.org/w/api.php?action=query&format=json&prop=extracts&redirects=1&explaintext=1&exsectionformat=plain&titles=$title";
    }

    private function getQueriesToTry($query, $language)
    {
        $tokens = $this->keywordExtractorService->extract($query, $language);
        return collect(array_merge(ngrams($tokens), $tokens));
    }
}