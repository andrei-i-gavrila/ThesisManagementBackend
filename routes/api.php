<?php

use App\Jobs\ProfessorDetailImporter;
use Illuminate\Support\Facades\Route;

Route::namespace("Auth")->prefix("auth")->group(function () {
    Route::post("register", "RegisterController");
    Route::post("login", "LoginController");
    Route::post("logout", "LogoutController")->middleware(["auth:api"]);
});


Route::middleware(["auth:api"])->group(function () {
    Route::namespace("User")->prefix("user")->group(function () {
        Route::get("me", "UserController@loggedUser");
    });

    Route::prefix('sessions')->group(function () {
        Route::get('/', "ExamSessionController@index");
        Route::post('/', "ExamSessionController@create");
        Route::delete('/{examSession}', "ExamSessionController@delete");
    });

    Route::prefix("professors")->group(function () {
        Route::post("/", "ProfessorsController@create");
        Route::get("/", "ProfessorsController@getAll");
        Route::delete("{user}", "ProfessorsController@delete");
        Route::get("{user}", "ProfessorsController@get");
        Route::post("{user}/toggle/coordinator", "ProfessorsController@toggleCoordinator");
        Route::post("{user}/toggle/evaluator", "ProfessorsController@toggleEvaluator");
    });
});

Route::get('/', function () {
    dispatch_now(new ProfessorDetailImporter('mircea@cs.ubbcluj.ro'));
});