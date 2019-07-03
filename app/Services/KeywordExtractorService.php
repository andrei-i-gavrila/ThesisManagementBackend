<?php


namespace App\Services;


use Illuminate\Support\Str;
use StopWordFactory;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Filters\LambdaFilter;
use TextAnalysis\Filters\LowerCaseFilter;
use TextAnalysis\Filters\PossessiveNounFilter;
use TextAnalysis\Filters\StopWordsFilter;
use TextAnalysis\Filters\TrimFilter;
use TextAnalysis\Stemmers\SnowballStemmer;
use TextAnalysis\Tokenizers\GeneralTokenizer;

class KeywordExtractorService
{

    /**
     * @var GeneralTokenizer
     */
    private $tokenizer;
    /**
     * @var array
     */
    private $filters;

    private $stopwords = [];

    private $stopwordsFiles = [
        'ro' => 'stop-words_romanian_1_ro.txt',
        'en' => 'stop-words_english_6_en.txt'
    ];
    private $stemmers = [];

    public function __construct()
    {
        $this->tokenizer = new GeneralTokenizer(" \n\t\r,-!?/=&._");
        /** @noinspection PhpParamsInspection */
        $charFilter = new LambdaFilter(function ($word) {
            return strlen($word) <= 2 ? null : $word;
        });

        /** @noinspection PhpParamsInspection */
        $wordsFilter = new LambdaFilter(function ($word) {
            return preg_replace('/[^a-z]/', '', $word);
        });

        /** @noinspection PhpParamsInspection */
        $asciiFilter = new LambdaFilter(function ($word) {
            return Str::ascii($word,  'ro');
        });

        $this->filters = [
            new LowerCaseFilter(),
            $asciiFilter,
            $wordsFilter,
            new TrimFilter(),
            $charFilter,
            new PossessiveNounFilter(),
        ];

    }

    public function extract($content, $language)
    {
        return $this->transformDocument($language, new TokensDocument($this->tokenizer->tokenize($content)))->getDocumentData();
    }

    private function transformDocument($language, $document)
    {

        foreach ($this->getFilters($language) as $filter) {
            $document->applyTransformation($filter);
        }
        $document->applyStemmer($this->getStemmer($language));
        return $document;
    }

    private function getFilters($language)
    {
        return array_merge($this->filters, [$this->stopWordsFilter($language)]);
    }

    private function stopWordsFilter($language)
    {
        if (!isset($this->stopwords[$language])) {
            $this->stopwords[$language] = new StopWordsFilter(StopWordFactory::get($this->stopwordsFiles[$language]));
        }

        return $this->stopwords[$language];
    }

    private function getStemmer($language)
    {
        if (!isset($this->stemmers[$language])) {
            $this->stemmers[$language] = new SnowballStemmer(['en' => 'English', 'ro' => 'Romanian'][$language]);
        }

        return $this->stemmers[$language];
    }


}