<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::post('/logout',[AuthController::class, 'logout']);

// =========profile user========

Route::get('user',[ProfileController::class,'index']);
Route::get('user/show/{id}', [ProfileController::class, 'show']);
Route::put('user/edit/{id}', [ProfileController::class, 'update']);
// ============="""=======

Route::middleware('auth:sanctum')->group(function(){
  Route::post('/profileupload',[ProfileController::class,'store']);
  Route::resource('/post',PostController::class);
  Route::resource('/like',LikeController::class);
  Route::resource('/comment',CommentController::class);

});

