<?php

namespace App\Console\Commands;

use App\Jobs\RolesAndPermissionsInitializer;
use Illuminate\Console\Command;

class UpdateRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the final role assignment';


    public function handle()
    {
        dispatch_now(new RolesAndPermissionsInitializer());
    }
}
