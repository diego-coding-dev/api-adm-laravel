p
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('public')->group(function () {
    /**
     * rota authentication
     */
    Route::prefix('authentication')->group(function () {
        Route::controller(\App\Http\Controllers\Api\AuthenticationController::class)->group(function () {
            Route::post('check-email', 'checkEmail');
            Route::post('set-password', 'setPassword');
            route::post('', 'makeAuthentication');
        });
    });
});
/**
 * rota private
 */
Route::prefix('private')->middleware(['auth:sanctum'])->group(function () {
    Route::delete('logout', [\App\Http\Controllers\Api\AuthenticationController::class, 'logout']);
    /**
     * rota profile
     */
    Route::prefix('profile')->group(function () {
        Route::controller(\App\Http\Controllers\Api\ProfileController::class)->group(function () {
            Route::get('show', 'show');
            Route::put('update-email', 'updateEmail');
            Route::put('update-password', 'updatePassword');
        });
    });
    /**
     * rota employees
     */
    Route::prefix('employees')->middleware(['ability:list-employee,create-employee,show-employee,update-employee'])->group(function () {
        Route::controller(\App\Http\Controllers\Api\Rh\EmployeeController::class)->group(function () {
            Route::get('list', 'list');
            Route::post('create', 'create');
            Route::put('deactive/{id}', 'deactive');
            Route::put('active/{id}', 'active');
        });
    });
    /**
     * rota clients
     */
    Route::prefix('clients')->middleware(['ability:list-client,create-client,show-client,update-client'])->group(function () {
        Route::controller(\App\Http\Controllers\Api\Rh\ClientController::class)->group(function () {
            Route::get('list', 'list');
            Route::post('create', 'create');
            Route::get('show/{id}', 'show');
            Route::put('update/{id}', 'update');
            Route::delete('delete/{id}', 'delete');
        });
    });
    /**
     * rota orders
     */
    Route::prefix('orders')->middleware(['ability:list-order,create-order,show-order,update-order'])->group(function () {
        Route::controller(\App\Http\Controllers\Api\Service\OrderController::class)->group(function () {
            Route::get('list', 'list');
            Route::post('create', 'create');
            Route::get('show/{id}', 'show');
            Route::put('finish/{id}', 'finish')->middleware(['hasItemInCart']);
            Route::delete('cancel/{id}', 'cancel');
        });
    });
    /**
     * rota type_products
     */
    Route::prefix('type-products')->middleware(['ability:list-product,create-product,show-product,update-product'])->group(function () {
        Route::controller(\App\Http\Controllers\Api\Storage\TypeProductController::class)->group(function () {
            Route::get('list', 'list');
            Route::post('create', 'create');
            Route::get('show/{id}', 'show');
            Route::put('update/{id}', 'update');
            Route::delete('delete/{id}', 'delete')->middleware(['hasProduct']);
        });
    });
    /**
     * rota products
     */
    Route::prefix('products')->middleware(['ability:list-product,create-product,show-product,update-product'])->group(function () {
        Route::controller(\App\Http\Controllers\Api\Storage\ProductController::class)->group(function () {
            Route::get('list', 'list');
            Route::post('create', 'create');
            Route::get('show/{id}', 'show');
            Route::put('update/{id}', 'update');
            Route::post('update-image/{id}', 'updateImage');
            Route::delete('delete/{id}', 'delete')->middleware(['inStock']);
        });
    });
    /**
     * rota storages
     */
    Route::prefix('storages')->middleware(['ability:list-product,create-product,show-product,update-product'])->group(function () {
        Route::controller(\App\Http\Controllers\Api\Storage\StorageController::class)->group(function () {
            Route::get('list', 'list');
            Route::get('show/{id}', 'show');
            Route::put('update/{id}', 'update');
        });
    });
    /**
     * rota order-items
     */
    Route::prefix('order-items')->middleware(['ability:list-product,create-product,show-product,update-product'])->group(function () {
        Route::controller(\App\Http\Controllers\Api\Storage\OrderItemController::class)->group(function () {
            Route::get('list/{order_id}', 'list');
            Route::post('add/{order_id}', 'add')->middleware(['beforeAddOrderItem']);
            Route::get('show/{id}', 'show');
            Route::put('add-quantity/{id}', 'addQuantity')->middleware(['hasEnoughQuantity']);
            Route::put('remove-quantity/{id}', 'removeQuantity');
            Route::delete('remove/{id}', 'remove');
        });
    });
});
