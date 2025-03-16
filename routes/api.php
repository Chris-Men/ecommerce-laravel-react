<?php

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
use Illuminate\Support\Facades\Route;

// Rutas públicas para autenticación
Route::post('admin/login', [AdminController::class, "auth"])->name("admin.auth");

// Rutas protegidas con autenticación vía API
Route::middleware('auth:sanctum')->prefix("admin")->group(function () {

    // Dashboard y logout
    Route::get('dashboard', [AdminController::class, "index"])->name("admin.index");
    Route::post('logout', [AdminController::class, "logout"])->name("admin.logout");

    // CRUDs con resource controllers
    $resources = [
        'categories' => CategoryController::class,
        'brands' => BrandController::class,
        'colors' => ColorController::class,
        'sizes' => SizeController::class,
        'products' => ProductController::class,
        'coupons' => CouponController::class,
    ];

    foreach ($resources as $key => $controller) {
        Route::apiResource($key, $controller)->names([
            'index' => "admin.$key.index",
            'store' => "admin.$key.store",
            'show' => "admin.$key.show",
            'update' => "admin.$key.update",
            'destroy' => "admin.$key.destroy",
        ]);
    }

    // Órdenes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, "index"])->name("admin.orders.index");
        Route::put('{order}/update', [OrderController::class, "updateDeliveredAtDate"])->name("admin.orders.update");
        Route::delete('{order}/delete', [OrderController::class, "delete"])->name("admin.orders.delete");
    });

    // Reseñas
    Route::prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, "index"])->name("admin.reviews.index");
        Route::patch('{review}/{status}/update', [ReviewController::class, "toggleApproveStatus"])->name("admin.reviews.update");
        Route::delete('{review}/delete', [ReviewController::class, "delete"])->name("admin.reviews.delete");
    });

    // Usuarios
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, "index"])->name("admin.users.index");
        Route::delete('{user}/delete', [UserController::class, "delete"])->name("admin.users.delete");
    });
});
