<?php

namespace App\Jobs;

use App\Models\DomainOfInterest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProfessorDetailImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var User
     */
    private $professor;


    private $urls = [
        'ro' => 'http://www.cs.ubbcluj.ro/despre-facultate/structura/departamentul-de-informatica/',
        'en' => 'http://www.cs.ubbcluj.ro/about-the-faculty/departments/department-of-computer-science/'
    ];

    private $regexIdentifier = [
        'ro' => 'interes',
        'en' => 'interest'
    ];

    public function __construct(User $professor)
    {
        $this->professor = $professor;
    }

    public function handle()
    {
        $this->import('ro');
        $this->import('en');
    }

    private function import($language)
    {
        $pageData = file_get_contents($this->urls[$language]);
        $re = $this->makeRegex($this->professor->email, $language);


        Log::info($re);

        preg_match($re, $pageData, $matches);

        if (empty($matches)) {
            return;
        }

        if (!$this->professor->image_url) {
            $this->professor->update(['image_url' => $matches[1]]);
        }

        $domains = collect(explode(', ', $matches[2]))->map(function ($name) use ($language) {
            $name = $this->transformName($name);
            return DomainOfInterest::firstOrCreate(compact('name', 'language'))->id;
        });


        $this->professor->domainsOfInterest()->syncWithoutDetaching($domains);

    }

    private function makeRegex($email, $language)
    {
        $emailRegex = str_replace(['@', '.'], ['\\[at\\]', '\\.'], $email);
        return '/src=[\'"](.*?)[\'"](?:.*\n){2,3}.*?' . $emailRegex . '(?:.*\n){2,3}.*?' . $this->regexIdentifier[$language] . ':\s?(.*?)</m';


    }

    private function transformName($name)
    {
        return str_replace(['instruire', 'Instruire'], ['învățare', 'Învățare'], $name);
    }

}
