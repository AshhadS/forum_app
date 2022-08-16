<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User;
use App\Post;

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

// User
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Posts
Route::post('posts/search', [App\Http\Controllers\PostController::class, 'search'])->middleware('auth:sanctum');
Route::post('posts/{post}/approve', [App\Http\Controllers\PostController::class, 'approve'])->middleware('auth:sanctum');
Route::get('posts/pending', [App\Http\Controllers\PostController::class, 'pending_posts'])->middleware('auth:sanctum');
Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');


// Comments
Route::get('comments/{model}/{model_id}', [App\Http\Controllers\CommentsController::class, 'index'])->middleware('auth:sanctum');
Route::post('comments/{model}/{model_id}', [App\Http\Controllers\CommentsController::class, 'store'])->middleware('auth:sanctum');
Route::delete('comments/{comment}', [App\Http\Controllers\CommentsController::class, 'destroy'])->middleware('auth:sanctum');