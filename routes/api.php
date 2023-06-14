<?php

use Illuminate\Support\Facades\Route;

/**
 * API: middleware: ''
 */
Route::group(['as' => 'api.'], function () {

    /**
     * Employees
     */
    Route::apiResource('employees', \App\Http\Controllers\Employees\EmployeeController::class);

});
