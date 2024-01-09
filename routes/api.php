<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    ProductController,
    CampaignController,
    PostController
};

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

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
});

Route::prefix('campaigns')->group(function () {
    Route::get('/', [CampaignController::class, 'index']);
    Route::get('/{id}', [CampaignController::class, 'show']);
});

Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']);
    Route::get('/{id}', [PostController::class, 'show']);
});

// protected routes
Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
    });

    Route::prefix('products')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/deleteMany', [CampaignController::class, 'deleteMany']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    Route::prefix('campaigns')->group(function () {
        Route::post('/', [CampaignController::class, 'store']);
        Route::put('/{id}', [CampaignController::class, 'update']);
        Route::delete('/deleteMany', [CampaignController::class, 'deleteMany']);
        Route::delete('/{id}', [CampaignController::class, 'destroy']);
    });

    Route::prefix('posts')->group(function () {
        Route::post('/', [PostController::class, 'store']);
        Route::put('/{id}', [PostController::class, 'update']);
        Route::delete('/deleteMany', [CampaignController::class, 'deleteMany']);
        Route::delete('/{id}', [PostController::class, 'destroy']);
    });
});
