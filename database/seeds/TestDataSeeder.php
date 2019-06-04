<?php

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => "Super admin",
            'email' => 'admin@admin.com',
            'password' => Hash::make("admin"),
            'activated' => 1,
        ])->assignRole(Role::findOrCreate(Roles::SUPER_ADMIN));
    }
}
