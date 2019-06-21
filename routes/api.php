<?php

use App\Events\TestEvent;
use App\Jobs\EvaluatorKeywordExtractor;
use App\Jobs\ProfessorImporter;
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
        Route::post("/", "ExamSessionController@create");
        Route::delete("/{examSession}", "ExamSessionController@delete");

        Route::get("/{examSession}/gradingCategory", "GradingCategoryController@getCategories");
        Route::post("/{examSession}/gradingCategory", "GradingCategoryController@saveCategory");

        Route::post("/{examSession}/committee", "CommitteeController@create");
        Route::get("/{examSession}/committee", "CommitteeController@get");
        Route::post("/{examSession}/randomOrder", "ExamSessionController@randomAssignment");
        Route::post("/{examSession}/lexicalOrder", "ExamSessionController@lexicalOrderAssignment");
    });


    Route::prefix("gradingCategory/{gradingCategory}")->group(function () {
        Route::delete("", "GradingCategoryController@deleteCategory");
        Route::post("", "GradingCategoryController@updateCategory");
        Route::post("/increment", "GradingCategoryController@incrementOrder");
        Route::post("/decrement", "GradingCategoryController@decrementOrder");
    });

    Route::prefix("professors")->group(function () {
        Route::post("/", "ProfessorsController@create");
        Route::get("/", "ProfessorsController@getAll");
        Route::delete("{user}", "ProfessorsController@delete");
        Route::get("{user}", "ProfessorsController@get");
        Route::post("{user}/reimport", "ProfessorsController@reimportDetails");
    });

    Route::prefix('/doi')->group(function() {
        Route::get('/{user}', "DomainOfInterestController@get");
        Route::post('', "DomainOfInterestController@create");
        Route::delete('/{domainOfInterest}', "DomainOfInterestController@remove");
    });

    Route::prefix("students")->group(function () {
        Route::post("/", "StudentsController@create");
        Route::get("/", "StudentsController@getMyStudents");
        Route::delete("{user}", "StudentsController@delete");
        Route::get("{user}", "StudentsController@get");
    });

    Route::prefix("revisions")->group(function () {
        Route::post("/{paper}", "PaperRevisionController@create");
        Route::get("/{paperRevision}/download", "PaperRevisionController@download");

        Route::post('{paperRevision}/messages', "CommentController@create");
        Route::get('{paperRevision}/messages', "CommentController@getForPaper");
    });


    Route::prefix("papers")->group(function () {
        Route::post("/{examSession}", "PaperController@updateDetails");
        Route::get("/{examSession}/user/{user}", "PaperController@getWithRevisions");
    });


    Route::prefix('comments')->group(function () {
        Route::post("{comment}", "CommentController@update");
        Route::delete("{comment}", "CommentController@delete");
    });

    Route::prefix('review/{paper}')->group(function () {
        Route::get("", "FinalReviewController@get");
        Route::get("download", "FinalReviewController@download");
        Route::post("", "FinalReviewController@store");
        Route::delete("", "FinalReviewController@delete");
    });


    Route::prefix('committee/{committee}')->group(function () {
        Route::post("", "CommitteeController@update");
        Route::delete("", "CommitteeController@delete");
    });

    Route::prefix('liveGrading')->group(function() {
        Route::get('/{examSession}/papers', "LiveGradingController@papers");
        Route::get('/{examSession}/committee', "LiveGradingController@committee");
        Route::get('/papers/{paper}', "LiveGradingController@paperData");
        Route::get('/papers/{paper}/grades', "LiveGradingController@grades");
        Route::get('/papers/{paper}/grades', "LiveGradingController@grades");
        Route::post('/papers/{paper}/grades/{category}', "LiveGradingController@setGrade");
    });
});


Route::get("/testi", function () {
    dispatch_now(new ProfessorImporter());
});

Route::get("/test", function () {
    dispatch_now(new EvaluatorKeywordExtractor());
});

Route::get('/testreview', function () {
    return PDF::loadView('pdf.review')->stream();
});

Route::get('/testreviewh', function () {
    return view('pdf.review');
});