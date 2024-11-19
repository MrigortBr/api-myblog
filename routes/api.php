<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\PostsController;
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


Route::group([] ,function (){
    Route::post("/register", [AuthController::class, 'register']);
    Route::post("/login", [AuthController::class, 'login']);
    Route::post("/logout", [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/posts', [PostsController::class, 'show']);
Route::middleware('auth:sanctum')->post('/post', [PostsController::class, 'create']);
Route::middleware('auth:sanctum')->get('/post/{id}', [PostsController::class, 'listById']);
Route::middleware('auth:sanctum')->get('/myposts', [PostsController::class, 'listMyPosts']);

Route::middleware('auth:sanctum')->put('/post/{id}', [PostsController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/post/{id}', [PostsController::class, 'delete']);

Route::middleware('auth:sanctum')->post('/comment/{idpost}', [CommentsController::class, 'comment']);

Route::middleware('auth:sanctum')->get('/comments', [CommentsController::class, 'listMyComments']);
Route::middleware('auth:sanctum')->get('/comment/{idComment}', [CommentsController::class, 'listById']);

Route::middleware('auth:sanctum')->put('/comment/{idComment}', [CommentsController::class, 'update']);

Route::middleware('auth:sanctum')->delete('/comment/{idComment}', [CommentsController::class, 'delete']);



