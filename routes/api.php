<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\Api\ProductController;

use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\AuthController;



// Ruta pública para login (JWT)
Route::post('admin/login', [AuthController::class, 'login'])->name('admin.login');

// Ruta para obtener el usuario autenticado
Route::middleware('jwt.auth')->get('admin/me', [AuthController::class, 'me'])->name('admin.me');

// Ruta para logout
Route::middleware('jwt.auth')->post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Rutas protegidas con JWT
Route::middleware('jwt.auth')->prefix('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.index');

    $resources = [
        'categories' => CategoryController::class,
        'brands' => BrandController::class,
        'colors' => ColorController::class,
        'sizes' => SizeController::class,
        'products' => \App\Http\Controllers\Api\ProductController::class,
        'coupons' => CouponController::class,
        'orders' => OrderController::class,
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


    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, "index"])->name("admin.orders.index");
        Route::put('{order}/update', [OrderController::class, "updateDeliveredAtDate"])->name("admin.orders.update");
        Route::delete('{order}/delete', [OrderController::class, "delete"])->name("admin.orders.delete");
    });

    Route::prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, "index"])->name("admin.reviews.index");
        Route::post('/', [ReviewController::class, "store"])->name("admin.reviews.store");

        Route::patch('{review}/{status}/update', [ReviewController::class, "toggleApproveStatus"])->name("admin.reviews.update");
        Route::delete('{review}/delete', [ReviewController::class, "delete"])->name("admin.reviews.delete");
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, "index"])->name("admin.users.index");
        Route::post('/', [UserController::class, 'store'])->name("admin.users.store");

        Route::delete('{user}/delete', [UserController::class, "delete"])->name("admin.users.delete");
    });

    Route::prefix('admin/coupons')->group(function () {
        Route::post('/', [CouponController::class, 'store'])->name('admin.coupons.store');
    });

    Route::prefix('admin/brands')->middleware('auth:api')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('admin.brands.index');
        Route::post('/', [BrandController::class, 'store'])->name('admin.brands.store');
        Route::get('{brand}', [BrandController::class, 'show'])->name('admin.brands.show');
        Route::put('{brand}', [BrandController::class, 'update'])->name('admin.brands.update');
        Route::delete('{brand}', [BrandController::class, 'destroy'])->name('admin.brands.destroy');
    });

    Route::middleware('jwt.auth')->prefix('admin/orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::put('{order}/update', [OrderController::class, 'updateDeliveredAtDate'])->name('admin.orders.update');
        Route::delete('{order}/delete', [OrderController::class, 'delete'])->name('admin.orders.delete');
    });


    Route::middleware('jwt.auth')->prefix('admin')->group(function () {
        Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class)->names([
            'index' => 'admin.products.index',
            'store' => 'admin.products.store',
            'show' => 'admin.products.show',
            'update' => 'admin.products.update',
            'destroy' => 'admin.products.destroy',
        ]);
    });



});
