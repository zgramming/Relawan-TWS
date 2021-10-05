<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\TypeOrganizationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/// START CATEGORY API
Route::get('category', [CategoryController::class, 'get']);
Route::get('category/{id}', [CategoryController::class, 'get']);
Route::post('category', [CategoryController::class, 'create']);
Route::patch('category', [CategoryController::class, 'update']);
Route::delete('category', [CategoryController::class, 'delete']);
/// END CATEGORY API

/// START [TYPE ORGANIZATION] API
Route::get('type_organization', [TypeOrganizationController::class, 'get']);
Route::get('type_organization/{id}', [TypeOrganizationController::class, 'get']);
Route::post('type_organization', [TypeOrganizationController::class, 'create']);
Route::patch('type_organization', [TypeOrganizationController::class, 'update']);
Route::delete('type_organization', [TypeOrganizationController::class, 'delete']);
/// END [TYPE ORGANIZATION] API

/// START [USER] API
Route::get('user', [UserController::class, 'get']);
Route::get('user/{id}', [UserController::class, 'get']);
Route::post('user', [UserController::class, 'create']);
Route::post('login', [UserController::class, 'login']);
Route::patch('user', [UserController::class, 'update']);
/// END [USER] API

/// START [ORGANIZATION] API
Route::get('organization', [OrganizationController::class, 'get']);
Route::get('organization/{id}', [OrganizationController::class, 'get']);
Route::post('organization', [OrganizationController::class, 'create']);
Route::put('organization/update', [OrganizationController::class, 'update']);
/// END [ORGANIZATION] API
