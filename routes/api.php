<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    ProductController,
    CampaignController,
    CampaignAttendanceController,
    PostController,
    PostCommentController,
    UserController,
    UserProfileAddressController,
    RoleController,
    LocationController,
    DashboardController,
    OrderController
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

Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/latest', [ProductController::class, 'latest']);
    Route::get('/{slug}/details', [ProductController::class, 'showBySlug']);
    Route::get('/{id}', [ProductController::class, 'show']);
});

Route::prefix('campaigns')->group(function () {
    Route::get('/', [CampaignController::class, 'index']);
    Route::get('/upcoming', [CampaignController::class, 'upcoming']);
    Route::post('/participate', [CampaignController::class, 'participate']);
    Route::delete('/unsubscribe/{campaingId}/{userId}', [CampaignController::class, 'unsubscribe']);
    Route::prefix('{id}')->group(function () {
        Route::get('/', [CampaignController::class, 'show']);
    });
});

Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']);

    Route::prefix('{id}/comments')->group(function () {
        Route::post('/', [PostCommentController::class, 'store']);

        Route::prefix('{commentId}')->group(function () {
            Route::post('/reply', [PostCommentController::class, 'storeReply']);
        });
    });

    Route::get('/{slug}', [PostController::class, 'show']);
});

Route::prefix('locations')->group(function () {
    Route::get('/', [LocationController::class, 'index']);
    Route::get('/types', [LocationController::class, 'getTypes']);
    Route::get('/{id}', [LocationController::class, 'show']);
});

Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'create']);

    Route::prefix('{orderNumber}/payment')->group(function () {
        Route::get('/access', [OrderController::class, 'paymentAccess']);
        Route::put('/status', [OrderController::class, 'updatePaymentStatus']);
    });
});

// protected routes
Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);

        Route::prefix('{id}')->group(function () {
            Route::put('/change-password', [AuthController::class, 'changePassword']);
        });
    });

    Route::prefix('products')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/deleteMany', [ProductController::class, 'deleteMany']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    Route::prefix('campaigns')->group(function () {
        Route::post('/', [CampaignController::class, 'store']);
        Route::delete('/deleteMany', [CampaignController::class, 'deleteMany']);

        Route::prefix('attendances')->group(function () {
            Route::delete('/deleteMany', [CampaignAttendanceController::class, 'deleteMany']);
            Route::delete('/{id}', [CampaignAttendanceController::class, 'destroy']);
        });

        Route::prefix('{id}')->group(function () {
            Route::put('/', [CampaignController::class, 'update']);
            Route::delete('/', [CampaignController::class, 'destroy']);
            Route::get('/attendances', [CampaignAttendanceController::class, 'campaignAttendances']);
        });
    });

    Route::prefix('posts')->group(function () {
        Route::post('/', [PostController::class, 'store']);
        Route::put('/{id}', [PostController::class, 'update']);
        Route::delete('/deleteMany', [CampaignController::class, 'deleteMany']);
        Route::delete('/{id}', [PostController::class, 'destroy']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/all', [UserCOntroller::class, 'getAll']);
        Route::post('/', [UserController::class, 'store']);
        Route::delete('/deleteMany', [UserController::class, 'deleteMany']);

        Route::prefix('/addresses/{profileId}')->group(function () {
            Route::get('/', [UserProfileAddressController::class, 'index']);
            Route::post('/', [UserProfileAddressController::class, 'store']);
            Route::put('/{id}', [UserProfileAddressController::class, 'update']);
            Route::delete('/{id}', [UserProfileAddressController::class, 'destroy']);
        });

        Route::prefix('{id}')->group(function () {
            Route::get('/', [UserController::class, 'show']);
            Route::get('/attendances', [CampaignAttendanceController::class, 'userAttendances']);
            Route::put('/', [UserController::class, 'update']);
            Route::delete('/', [UserController::class, 'destroy']);
        });
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
    });

    Route::prefix('locations')->group(function () {
        Route::post('/', [LocationController::class, 'store']);
        Route::put('/{id}', [LocationController::class, 'update']);
        Route::delete('/{id}', [LocationController::class, 'destroy']);
    });
});
