<?php

use App\Enums\Permissions;
use App\Events\TestEvent;
use App\Services\wikidrain;
use Illuminate\Support\Facades\Route;

Route::namespace("Auth")->prefix("auth")->group(function () {
    Route::post("register", "RegisterController");
    Route::post("login", "LoginController");
    Route::post("logout", "LogoutController")->middleware(["auth:api"]);
});


Route::middleware(["auth"])->group(function () {
    Route::namespace("User")->prefix("user")->group(function () {
        Route::get("me", "UserController@loggedUser");
    });

    Route::prefix("sessions")->group(function () {
        Route::get("/", "ExamSessionController@index");
        Route::get("/{examSession}/gradingCategory", "GradingCategoryController@getCategories");

        Route::group(['middleware' => ['role_or_permission:' . Permissions::MANAGE_SESSIONS]], function () {
            Route::post("/", "ExamSessionController@create");
            Route::delete("/{examSession}", "ExamSessionController@delete");
        });

        Route::group(['middleware' => ['role_or_permission:' . Permissions::MANAGE_GRADING_SCHEMES]], function () {
            Route::post("/{examSession}/gradingCategory", "GradingCategoryController@saveCategory");
        });

        Route::group(['middleware' => ['role_or_permission:' . Permissions::MANAGE_COMMITTEES]], function () {
            Route::post("/{examSession}/committee", "CommitteeController@create");
            Route::get("/{examSession}/committee", "CommitteeController@get");
            Route::post("/{examSession}/randomOrder", "ExamSessionController@randomAssignment");
            Route::post("/{examSession}/smartOrder", "ExamSessionController@smartAssignment");
            Route::post("/{examSession}/lexicalOrder", "ExamSessionController@lexicalOrderAssignment");
            Route::post("/{examSession}/calculateScores", "ExamSessionController@calculateScores");
            Route::get("/{examSession}/getScore", "ExamSessionController@getScoreOfDistribution");
        });
    });


    Route::prefix("writtenGrades")->group(function () {
        Route::group(['middleware' => ['role_or_permission:' . Permissions::UPLOAD_WRITTEN_EXAM_GRADES]], function () {
            Route::post("{examSession}", "WrittenGradeUploadController@upload");
            Route::get("{examSession}", "WrittenGradeUploadController@getAll");
        });
    });

    Route::prefix("secretary")->group(function () {
        Route::group(['middleware' => ['role_or_permission:' . Permissions::GENERATE_DOCUMENTS]], function () {
            Route::get("/paper/{paper}", "SecretaryController@download");
            Route::get("{examSession}", "SecretaryController@getAll");
        });
    });

    Route::prefix("gradingCategory/{gradingCategory}")->middleware(['role_or_permission:' . Permissions::MANAGE_GRADING_SCHEMES])->group(function () {
        Route::delete("", "GradingCategoryController@deleteCategory");
        Route::post("", "GradingCategoryController@updateCategory");
        Route::post("/increment", "GradingCategoryController@incrementOrder");
        Route::post("/decrement", "GradingCategoryController@decrementOrder");
    });

    Route::prefix("professors")->middleware(['role_or_permission:' . Permissions::MANAGE_PROFESSORS])->group(function () {
        Route::post("/", "ProfessorsController@create");
        Route::get("/", "ProfessorsController@getAll");
        Route::delete("{user}", "ProfessorsController@delete");
        Route::get("{user}", "ProfessorsController@get");
        Route::post("{user}/reimport", "ProfessorsController@reimportDetails");
    });

    Route::prefix('/doi')->middleware(['role_or_permission:' . Permissions::MANAGE_KEYWORDS])->group(function () {
        Route::get('/{user}', "DomainOfInterestController@get");
        Route::post('', "DomainOfInterestController@create");
        Route::delete('/{domainOfInterest}', "DomainOfInterestController@remove");
    });

    Route::prefix("students")->middleware(['role_or_permission:' . Permissions::MANAGE_STUDENTS])->group(function () {
        Route::post("/", "StudentsController@create");
        Route::get("/", "StudentsController@getMyStudents");
        Route::delete("{user}", "StudentsController@delete");
        Route::get("{user}", "StudentsController@get");
    });

    Route::prefix("revisions")->group(function () {
        Route::post("/{paper}", "PaperRevisionController@create")->middleware(['role_or_permission:' . Permissions::MANAGE_THESIS_PAPERS]);
        Route::get("/{paperRevision}/download", "PaperRevisionController@download")->middleware(['role_or_permission:' . Permissions::SEE_LIST_OF_PAPERS . '|' . Permissions::GRADE]);

        Route::post('{paperRevision}/messages', "CommentController@create")->middleware(['role_or_permission:' . Permissions::DISCUSS_PAPERS]);
        Route::get('{paperRevision}/messages', "CommentController@getForPaper")->middleware(['role_or_permission:' . Permissions::SEE_LIST_OF_PAPERS]);
    });


    Route::prefix("papers")->group(function () {
        Route::post("/{examSession}", "PaperController@updateDetails")->middleware(['role_or_permission:' . Permissions::MANAGE_THESIS_PAPERS]);
        Route::get("/{examSession}/user/{user}", "PaperController@getWithRevisions")->middleware(['role_or_permission:' . Permissions::MANAGE_THESIS_PAPERS . '|' . Permissions::SEE_LIST_OF_PAPERS]);
    });


    Route::prefix('comments')->group(function () {
        Route::post("{comment}", "CommentController@update")->middleware(['role_or_permission:' . Permissions::DISCUSS_PAPERS]);
        Route::delete("{comment}", "CommentController@delete")->middleware(['role_or_permission:' . Permissions::DISCUSS_PAPERS]);
    });

    Route::prefix('review/{paper}')->group(function () {
        Route::get("", "FinalReviewController@get")->middleware(['role_or_permission:' . Permissions::SEE_LIST_OF_PAPERS]);
        Route::get("download", "FinalReviewController@download")->middleware(['role_or_permission:' . Permissions::SEE_LIST_OF_PAPERS . '|' . Permissions::GRADE]);
        Route::post("", "FinalReviewController@store")->middleware(['role_or_permission:' . Permissions::SEE_LIST_OF_PAPERS]);;
        Route::delete("", "FinalReviewController@delete")->middleware(['role_or_permission:' . Permissions::SEE_LIST_OF_PAPERS]);;
    });


    Route::prefix('committee/{committee}')->middleware(['role_or_permission:' . Permissions::MANAGE_COMMITTEES])->group(function () {
        Route::post("", "CommitteeController@update");
        Route::delete("", "CommitteeController@delete");
    });

    Route::prefix('liveGrading')->middleware(['role_or_permission:' . Permissions::GRADE])->group(function () {
        Route::get('/{examSession}/papers', "LiveGradingController@papers");
        Route::get('/{examSession}/committee', "LiveGradingController@committee");
        Route::get('/papers/{paper}', "LiveGradingController@paperData");
        Route::get('/papers/{paper}/grades', "LiveGradingController@grades");
        Route::get('/papers/{paper}/grades', "LiveGradingController@grades");
        Route::post('/papers/{paper}/grades/{category}', "LiveGradingController@setGrade");
    });
});