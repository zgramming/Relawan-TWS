<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TypeOrganizationController;
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
Route::put('category', [CategoryController::class, 'update']);
Route::delete('category', [CategoryController::class, 'delete']);
/// END CATEGORY API

/// START CATEGORY [TYPE ORGANIZATION] API
Route::get('type_organization', [TypeOrganizationController::class, 'get']);
Route::get('type_organization/{id}', [TypeOrganizationController::class, 'get']);
Route::post('type_organization', [TypeOrganizationController::class, 'create']);
Route::put('type_organization', [TypeOrganizationController::class, 'update']);
Route::delete('type_organization', [TypeOrganizationController::class, 'delete']);

/// END CATEGORY [TYPE ORGANIZATION] API
