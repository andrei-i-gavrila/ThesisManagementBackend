<?php

use App\Enums\Permissions;
use App\Enums\Roles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect((new ReflectionClass(Permissions::class))->getConstants())
            ->each(function ($permission) {
                Permission::create(['name' => $permission]);
            });

        $this->createRole(Roles::ADMIN)->givePermissionTo([
            Permissions::MANAGE_PROFESSORS
        ]);

        $this->createRole(Roles::PROFESSOR);

        $this->createRole(Roles::COORDINATOR)->givePermissionTo([
            Permissions::MANAGE_STUDENTS,
            Permissions::DISCUSS_PAPERS
        ]);
        $this->createRole(Roles::EVALUATOR)->givePermissionTo([
            Permissions::MANAGE_KEYWORDS,
            Permissions::SEE_STUDENTS
        ]);

        $this->createRole(Roles::STUDENT)->givePermissionTo([
            Permissions::MANAGE_THESIS_PAPERS,
            Permissions::DISCUSS_PAPERS
        ]);


        Permission::findByName(Permissions::LOGOUT)->assignRole(Role::all());
        $this->createRole(Roles::SUPER_ADMIN)->givePermissionTo(Permission::all());
    }

    /**
     * @param string $role
     * @return Model|Role
     */
    private function createRole(string $role)
    {
        return Role::create(['name' => $role]);
    }

}
