<?php

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
        Route::post("{user}/toggle/coordinator", "ProfessorsController@toggleCoordinator");
        Route::post("{user}/toggle/evaluator", "ProfessorsController@toggleEvaluator");
        Route::post("{user}/reimport", "ProfessorsController@reimportDetails");
    });

    Route::prefix("students")->group(function () {
        Route::post("/", "StudentsController@create");
        Route::get("/all", "StudentsController@getAll");
        Route::get("/", "StudentsController@getMyStudents");
        Route::delete("{user}", "StudentsController@delete");
        Route::get("{user}", "StudentsController@get");
    });
});