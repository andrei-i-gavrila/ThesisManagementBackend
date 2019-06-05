<?php

namespace App\Jobs;

use App\Enums\Permissions;
use App\Enums\Roles;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsInitializer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $roles = [
        Roles::ADMIN => [
            Permissions::MANAGE_PROFESSORS,
            Permissions::MANAGE_SESSIONS,
            Permissions::MANAGE_GRADING_SCHEMES
        ],
        Roles::PROFESSOR => [],
        Roles::COORDINATOR => [
            Permissions::MANAGE_STUDENTS,
            Permissions::DISCUSS_PAPERS
        ],
        Roles::EVALUATOR => [
            Permissions::MANAGE_KEYWORDS,
            Permissions::SEE_STUDENTS,
            Permissions::GRADE,
        ],
        Roles::STUDENT => [
            Permissions::MANAGE_THESIS_PAPERS,
            Permissions::DISCUSS_PAPERS,
            Permissions::SEE_EVALUATORS
        ]
    ];

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->roles as $roleName => $permissions) {
            $role = Role::findOrCreate($roleName);
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission])->assignRole([$role, Roles::SUPER_ADMIN]);
            }
        }
    }

}
