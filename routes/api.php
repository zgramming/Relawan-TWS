<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventJoinedController;
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
Route::put('category/{id}', [CategoryController::class, 'update']);
Route::delete('category/{id}', [CategoryController::class, 'delete']);
/// END CATEGORY API

/// START [TYPE ORGANIZATION] API
Route::get('type_organization', [TypeOrganizationController::class, 'get']);
Route::get('type_organization/{id}', [TypeOrganizationController::class, 'get']);
Route::post('type_organization', [TypeOrganizationController::class, 'create']);
Route::put('type_organization/{id}', [TypeOrganizationController::class, 'update']);
Route::delete('type_organization/{id}', [TypeOrganizationController::class, 'delete']);
/// END [TYPE ORGANIZATION] API

/// START [USER] API
Route::get('user', [UserController::class, 'get']);
Route::get('user/{id}', [UserController::class, 'get']);
Route::post('user', [UserController::class, 'create']);
Route::post('login', [UserController::class, 'login']);
Route::put('user/{id}', [UserController::class, 'update']);
Route::delete('user/{id}', [UserController::class, 'delete']);
/// END [USER] API

/// START [ORGANIZATION] API
Route::get('organization', [OrganizationController::class, 'get']);
Route::get('organization/{id}', [OrganizationController::class, 'get']);
// Route::post('organization', [OrganizationController::class, 'create']);
Route::put('organization', [OrganizationController::class, 'update']);
/// END [ORGANIZATION] API

/// START [EVENT] API
Route::get('event', [EventController::class, 'get']);
Route::get('event/nearestDate', [EventController::class, 'nearestDate']);
Route::get('event/forYou', [EventController::class, 'forYou']);
Route::get('event/{id}', [EventController::class, 'get']);
Route::post('event', [EventController::class, 'create']);
Route::put('event/{id}', [EventController::class, 'update']);
Route::delete('event/{id}', [EventController::class, 'delete']);
/// END [EVENT] API

/// START [EVENT_CATEGORY] API
Route::get('eventCategory', [EventCategoryController::class, 'get']);
Route::get('eventCategory/{id}', [EventCategoryController::class, 'get']);
Route::post('eventCategory', [EventCategoryController::class, 'create']);
Route::put('eventCategory/{id}', [EventCategoryController::class, 'update']);
Route::delete('eventCategory/{id}', [EventCategoryController::class, 'delete']);
/// END [EVENT_CATEGORY] API

/// START [EVENT_JOINED] API
Route::get('eventJoined', [EventJoinedController::class, 'get']);
Route::get('eventJoined/{id}', [EventJoinedController::class, 'get']);
Route::post('eventJoined', [EventJoinedController::class, 'create']);
// Route::put('eventJoined/update', [EventJoinedController::class, 'update']);
Route::delete('eventJoined/{id}', [EventJoinedController::class, 'delete']);
/// END [EVENT_JOINED] API
