<?php

use Illuminate\Support\Facades\Route;

// =========================
// IMPORTACIÓN DE CONTROLADORES
// =========================

// Auth para admins
use App\Http\Controllers\Api\AuthController;

// Admin (panel de gestión)
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\UserController;

// Auth para usuarios
use App\Http\Controllers\Api\AuthUserController;

// Funcionalidad para usuarios
use App\Http\Controllers\Api\ProductController as UserProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController as UserOrderController;
use App\Http\Controllers\Api\CategoryController as UserCategoryController;
use App\Http\Controllers\Api\ReviewController as UserReviewController;

// =========================
// RUTAS PÚBLICAS
// =========================

// Login para admins
Route::post('admin/login', [AuthController::class, 'login']);

// Lista pública de admins
Route::get('admins', [AdminController::class, 'publicList']);


// =========================
// RUTAS PARA ADMINISTRADORES
// Protegidas por el middleware auth:admin-api
// =========================
Route::middleware('auth:admin-api')->prefix('admin')->group(function () {

    // Datos del admin autenticado
    Route::get('me', [AuthController::class, 'me']);

    // Cierre de sesión
    Route::post('logout', [AuthController::class, 'logout']);

    // Dashboard principal
    Route::get('dashboard', [AdminController::class, 'index']);




    // Recursos RESTful administrables
    $resources = [
        'categories' => CategoryController::class,
        'brands'     => BrandController::class,
        'colors'     => ColorController::class,
        'sizes'      => SizeController::class,
        'products'   => ProductController::class,
        'coupons'    => CouponController::class,
        'orders'     => OrderController::class,
    ];

    foreach ($resources as $key => $controller) {
        Route::apiResource($key, $controller)->names("admin.$key");
    }

Route::apiResource('brands', BrandController::class)
    ->parameters(['brands' => 'id'])
    ->names("admin.brands");





    // Funciones extra para pedidos
    Route::prefix('orders')->group(function () {
        Route::put('{order}/delivered', [OrderController::class, 'updateDeliveredAtDate']);
        Route::delete('{order}/delete', [OrderController::class, 'delete']);
    });

    // Gestión de reseñas (con prefijo 'admin')
    Route::apiResource('reviews', AdminReviewController::class)->names('admin.reviews');

    // Gestión completa de usuarios (admin)

Route::apiResource('users', UserController::class)->names('admin.users');


});








// =========================
// AUTENTICACIÓN DE USUARIOS
// =========================

Route::prefix('user')->group(function () {

    // Registro y login
    Route::post('register', [AuthUserController::class, 'register']);
    Route::post('login', [AuthUserController::class, 'login']);

    // Rutas protegidas para usuarios autenticados
    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthUserController::class, 'me']);
        Route::post('logout', [AuthUserController::class, 'logout']);
    });
});








// =========================
// FUNCIONALIDADES PARA USUARIOS AUTENTICADOS
// =========================

Route::middleware('auth:api')->group(function () {

    //cupones
    Route::get('coupons', [\App\Http\Controllers\Api\CouponController::class, 'index']);

    // Productos
    Route::get('products', [UserProductController::class, 'index']);
    Route::get('products/{id}', [UserProductController::class, 'show']);

    //categorias
    Route::get('categories', [UserCategoryController::class, 'index']);
    Route::get('categories/{id}', [UserCategoryController::class, 'show']);

    // Carrito de compras
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::put('/{id}', [CartController::class, 'update']);
        Route::delete('/{id}', [CartController::class, 'destroy']);
        Route::delete('/', [CartController::class, 'clear']);
        Route::post('/apply-coupon', [CartController::class, 'applyCoupon']);
        Route::get('/summary', [CartController::class, 'summary']);


    });

    // Órdenes
    Route::post('/orders/store', [UserOrderController::class, 'storeUserOrders']);
    Route::post('/pay-orders-stripe', [UserOrderController::class, 'payOrdersByStripe']);

    //reseñas
    Route::apiResource('reviews', UserReviewController::class);
});
