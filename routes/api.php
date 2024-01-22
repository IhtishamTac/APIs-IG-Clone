<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuhtController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserController;
use App\Models\Post;

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

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuhtController::class, 'register']);
        Route::post('login', [AuhtController::class, 'login']);
        Route::post('logout', [AuhtController::class, 'logout'])->middleware('auth:sanctum');
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::resource('posts', PostsController::class);
        Route::prefix('users')->group(function () {
            Route::get('', [UserController::class, 'getNotFollowed']);
            Route::get('{username}', [UserController::class, 'getDetailUser']);

            Route::post('{username}/follow', [FollowController::class, 'follAUser']);
            Route::delete('{username}/unfollow', [FollowController::class, 'unfollAUser']);

            Route::get('{username}/following', [FollowController::class, 'getFollowing']);
            Route::get('{username}/followers', [FollowController::class, 'getFollowers']);

            Route::put('{username}/accept', [FollowController::class, 'accUserFollow']);
        });
    });
});
