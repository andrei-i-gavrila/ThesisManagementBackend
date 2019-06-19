<?php

use App\Enums\Roles;
use App\Models\Committee;
use App\Models\ExamSession;
use App\Models\FinalReview;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     **/
    public function run()
    {
        User::create([
            'name' => "Super admin",
            'email' => 'admin@admin.com',
            'password' => Hash::make("admin"),
            'activated' => 1,
        ])->assignRole(Role::findOrCreate(Roles::SUPER_ADMIN));


        $examSession = ExamSession::create(['name' => 'test']);
        $studentRole = Role::findOrCreate(Roles::STUDENT);
        $professorRole = Role::findOrCreate(Roles::PROFESSOR);

        factory(User::class, 20)->create()->each(function ($user) use ($professorRole) {
            $user->assignRole($professorRole);
        });

        $examSession->finalReviews()->saveMany(factory(FinalReview::class, 100)->make([
            'exam_session_id' => $examSession->id
        ]));
        FinalReview::with('student')->get()->each(function ($review) use ($studentRole) {
            $review->student->assignRole($studentRole);
        });

        repeat(5, function ($i) use ($examSession) {
            Committee::create([
                'leader_id' => $i * 4 + 1,
                'member1_id' => $i * 4 + 2,
                'member2_id' => $i * 4 + 3,
                'secretary_id' => $i * 4 + 4,
                'exam_session_id' => $examSession->id
            ]);
        });
    }
}

function repeat($i, $fn)
{
    foreach (range(0, $i-1) as $idx) {
        $fn($idx);
    }
}
