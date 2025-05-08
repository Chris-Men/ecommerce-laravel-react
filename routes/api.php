<?php


//administrador "admin"
use Illuminate\Support\Facades\Route;
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



//user "usuario"
use App\Http\Controllers\Api\AuthUserController;



// Login sin protección
Route::post('admin/login', [AuthController::class, 'login'])->name('admin.login');
Route::get('admins', [AdminController::class, 'publicList']);


//Login de usuario
Route::prefix('user')->group(function () {
    Route::post('register', [AuthUserController::class, 'register']);
    Route::post('login', [AuthUserController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthUserController::class, 'me']);
        Route::post('logout', [AuthUserController::class, 'logout']);
    });
});






// Rutas protegidas con middleware y prefijo 'admin'
Route::middleware('auth:admin-api')->prefix('admin')->group(function () {

    // Perfil y logout
    Route::get('me', [AuthController::class, 'me'])->name('admin.me');
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Ruta de prueba protegida
    Route::get('protected', function () {
        return response()->json(['message' => 'Ruta protegida JWT funcionando']);
    });

    // Dashboard
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.index');

    // Recursos estándar
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
        Route::apiResource($key, $controller)->names([
            'index' => "admin.$key.index",
            'store' => "admin.$key.store",
            'show' => "admin.$key.show",
            'update' => "admin.$key.update",
            'destroy' => "admin.$key.destroy",
        ]);
    }

    // Rutas extra para pedidos
    Route::prefix('orders')->group(function () {
        Route::put('{order}/delivered', [OrderController::class, 'updateDeliveredAtDate'])->name('admin.orders.delivered');
        Route::delete('{order}/delete', [OrderController::class, 'delete'])->name('admin.orders.delete');
    });

    // Reseñas
    Route::prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('admin.reviews.index');
        Route::post('/', [ReviewController::class, 'store'])->name('admin.reviews.store');
        Route::patch('{review}/{status}/toggle', [ReviewController::class, 'toggleApproveStatus'])->name('admin.reviews.toggle');
        Route::delete('{review}/delete', [ReviewController::class, 'delete'])->name('admin.reviews.delete');
    });

    // Usuarios desde admin
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('/', [UserController::class, 'store'])->name('admin.users.store');
        Route::delete('{user}/delete', [UserController::class, 'delete'])->name('admin.users.delete');
    });








});
