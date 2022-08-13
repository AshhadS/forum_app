<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('posts/{id}/approve', [PostController::class, 'approve'])->middleware('auth:sanctum');
Route::get('posts/pending', [PostController::class, 'pending_posts'])->middleware('auth:sanctum');
Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');
