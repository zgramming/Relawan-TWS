<?php

use App\Http\Controllers\CategoryController;
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

Route::get('category', [CategoryController::class, 'get']);
Route::get('category/{id}', [CategoryController::class, 'get']);

Route::post('category', [CategoryController::class, 'create']);
Route::put('category', [CategoryController::class, 'update']);
Route::delete('category', [CategoryController::class, 'delete']);
