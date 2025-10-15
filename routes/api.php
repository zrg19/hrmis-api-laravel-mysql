<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\CustomerMeasurementController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('users/profile', [UserController::class, 'profile']);
    
    Route::group(['middleware' => 'role:Admin,Manager'], function () {
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{user}', [UserController::class, 'show']);
    });
    
    Route::group(['middleware' => 'role:Admin'], function () {
        Route::post('users', [UserController::class, 'store']);
        Route::put('users/{user}', [UserController::class, 'update']);
        Route::patch('users/{user}', [UserController::class, 'update']);
        Route::delete('users/{user}', [UserController::class, 'destroy']);
    });
    
    Route::get('users/email/{email}', [UserController::class, 'findByEmail']);
    
    Route::group(['middleware' => 'role:Admin,Manager'], function () {
        Route::apiResource('tasks', TaskController::class);
    });
    
    Route::apiResource('leaves', LeaveController::class);
    Route::get('leaves/user/{id}', [LeaveController::class, 'findByUserId']);
    Route::get('leaves/user/{id}/pending', [LeaveController::class, 'findPendingLeavesByUserId']);
    Route::get('leaves/user/{id}/approved', [LeaveController::class, 'findApprovedLeavesByUserId']);
    Route::get('leaves/user/{id}/rejected', [LeaveController::class, 'findRejectedLeavesByUserId']);
    
    // Customer Measurements routes
    Route::group(['middleware' => 'role:Admin,Manager'], function () {
        Route::apiResource('customer-measurements', CustomerMeasurementController::class);
        Route::get('customer-measurements/trashed', [CustomerMeasurementController::class, 'trashed']);
        Route::post('customer-measurements/{id}/restore', [CustomerMeasurementController::class, 'restore']);
        Route::delete('customer-measurements/{id}/force-delete', [CustomerMeasurementController::class, 'forceDelete']);
    });
});