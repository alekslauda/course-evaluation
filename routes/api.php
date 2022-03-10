<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V2\CourseController;
use App\Http\Controllers\Api\V1\CourseController as ServerSideTask;

/**
 * THIS IS A BONUS
 * I'VE JUST WANTED TO SHOW WHAT WOULD BE THE GOOD PRACTISES TO ME
 * IF WE FOLLOW RESTFUL API DESIGN
 * Endpoint design:
    ● The endpoint should be RESTful and naming recommendations compliant
    ● The endpoint should be designed following the best practices
    ● Request method - GET
 */

Route::group(['prefix' => 'v1'], function () {
    Route::get('/courses/evaluation', [ServerSideTask::class, 'evaluate']);
});

Route::group(['prefix' => 'v2'], function () {
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::get('/courses/{id}/evaluation', [CourseController::class, 'evaluate']);
});
