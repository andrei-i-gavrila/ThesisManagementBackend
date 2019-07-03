<?php


namespace App\Services;


use Exception;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;

class PdfReaderService
{
    /**
     * @var Parser
     */
    private $parser;


    public function __construct()
    {
        $this->parser = new Parser();
    }

    public function parse($file)
    {
        try {
            return $this->parser->parseFile($file)->getText();
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }

}