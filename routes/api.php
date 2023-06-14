<?php

use Illuminate\Support\Facades\Route;

/**
 * API: middleware: ''
 */
Route::group(['as' => 'api.'], function () {

    /**
     * Cats
     */
    Route::apiResource('cats', \App\Http\Controllers\Cats\CatController::class);

    /**
     * Employees
     */
    Route::apiResource('employees', \App\Http\Controllers\Employees\EmployeeController::class);

    /**
     * Shelters
     */
    Route::apiResource('shelters', \App\Http\Controllers\Shelters\ShelterController::class);

});
