<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InitDevEnv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reinits the app with dev settings';

    public function handle()
    {
        Artisan::call('migrate:fresh', ['--seed' => true]);
        Artisan::call('roles:update');
    }
}
