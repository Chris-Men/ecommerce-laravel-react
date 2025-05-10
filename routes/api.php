<?php

use Illuminate\Support\Facades\Route;

// Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\AuthController;

// User
use App\Http\Controllers\Api\AuthUserController;
use App\Http\Controllers\Api\ProductController as UserProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController as UserOrderController;

// ADMIN AUTH
Route::post('admin/login', [AuthController::class, 'login']);
Route::get('admins', [AdminController::class, 'publicList']);

Route::middleware('auth:admin-api')->prefix('admin')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('dashboard', [AdminController::class, 'index']);

    $resources = [
        'categories' => CategoryController::class,
        'brands' => BrandController::class,
        'colors' => ColorController::class,
        'sizes' => SizeController::class,
        'products' => ProductController::class,
        'coupons' => CouponController::class,
        'orders' => OrderController::class,
    ];

    foreach ($resources as $key => $controller) {
        Route::apiResource($key, $controller)->names("admin.$key");
    }

    Route::prefix('orders')->group(function () {
        Route::put('{order}/delivered', [OrderController::class, 'updateDeliveredAtDate']);
        Route::delete('{order}/delete', [OrderController::class, 'delete']);
    });

    Route::prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'index']);
        Route::post('/', [ReviewController::class, 'store']);
        Route::patch('{review}/{status}/toggle', [ReviewController::class, 'toggleApproveStatus']);
        Route::delete('{review}/delete', [ReviewController::class, 'delete']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::delete('{user}/delete', [UserController::class, 'delete']);
    });
});

// USER AUTH
Route::prefix('user')->group(function () {
    Route::post('register', [AuthUserController::class, 'register']);
    Route::post('login', [AuthUserController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthUserController::class, 'me']);
        Route::post('logout', [AuthUserController::class, 'logout']);
    });
});

// USER ZONE
Route::middleware('auth:api')->group(function () {
    Route::get('products', [UserProductController::class, 'index']);
    Route::get('products/{id}', [UserProductController::class, 'show']);

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::put('/{id}', [CartController::class, 'update']);
        Route::delete('/{id}', [CartController::class, 'destroy']);
        Route::delete('/', [CartController::class, 'clear']);
    });

    Route::post('/orders/store', [UserOrderController::class, 'storeUserOrders']);
    Route::post('/pay-orders-stripe', [UserOrderController::class, 'payOrdersByStripe']);
});
