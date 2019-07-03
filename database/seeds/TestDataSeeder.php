<?php

use App\Enums\Roles;
use App\Models\Committee;
use App\Models\ExamSession;
use App\Models\FinalReview;
use App\Models\GradingCategory;
use App\Models\Paper;
use App\Models\PaperRevision;
use App\Models\User;
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

        return;
        $examSession = ExamSession::create(['name' => 'iulie-2019-engleza', 'presentation_name' => "Iulie 2019 Engleza", 'department' => 'Informatica Engleza']);
        $studentRole = Role::findOrCreate(Roles::STUDENT);
        $professorRole = Role::findOrCreate(Roles::PROFESSOR);
        $evalRole = Role::findOrCreate(Roles::EVALUATOR);

        factory(User::class, 12)->create()->each(function ($user) use ($professorRole, $evalRole) {
            $user->assignRole($professorRole, $evalRole);
        });


        factory(FinalReview::class, 40)->create();

        Paper::with('student')->get()->each(function (Paper $paper) use ($studentRole) {
            $paper->student->assignRole($studentRole);

            $paper->revisions()->save(new PaperRevision(array(
                'filepath' => 'papers/admin@admin.com/2019-06-18-024337-Revision0.pdf',
                'name' => 'Revision0'
            )));
        });

        GradingCategory::create([
            'name' => 'Theory',
            'description' => 'Whatever',
            'points' => '3',
            'order' => 1,
            'exam_session_id' => 1
        ]);

        GradingCategory::create([
            'name' => 'Application',
            'description' => 'Whatever',
            'points' => '4',
            'order' => 2,
            'exam_session_id' => 1
        ]);

        GradingCategory::create([
            'name' => 'Complexity',
            'description' => 'Whatever',
            'points' => '5',
            'order' => 1,
            'parent_category_id' => 2,
            'exam_session_id' => 1
        ]);
        GradingCategory::create([
            'name' => 'Quality',
            'description' => 'Whatever',
            'points' => '5',
            'order' => 2,
            'parent_category_id' => 2,
            'exam_session_id' => 1
        ]);

        GradingCategory::create([
            'name' => 'Paper',
            'description' => 'Whatever',
            'points' => '3',
            'order' => 3,
            'exam_session_id' => 1
        ]);


        repeat(3, function ($i) use ($examSession) {
            Committee::create([
                'leader_id' => $i * 3 + 2,
                'member1_id' => $i * 3 + 3,
                'member2_id' => $i * 3 + 4,
                'exam_session_id' => $examSession->id
            ]);
        });
    }
}

function repeat($i, $fn)
{
    foreach (range(0, $i - 1) as $idx) {
        $fn($idx);
    }
}
