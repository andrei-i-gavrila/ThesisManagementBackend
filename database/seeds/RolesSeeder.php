<?php

use App\Enums\Permissions;
use App\Enums\Roles;
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
        Role::create(['name' => Roles::SUPER_ADMIN]);
        $admin = Role::create(['name' => Roles::ADMIN]);
        $coordinator = Role::create(['name' => Roles::COORDINATOR]);
        $student = Role::create(['name' => Roles::STUDENT]);


        Permission::create(['name' => Permissions::MANAGE_COORDINATORS])->assignRole($admin);
        Permission::create(['name' => Permissions::MANAGE_STUDENTS])->assignRole($coordinator);

    }
}
