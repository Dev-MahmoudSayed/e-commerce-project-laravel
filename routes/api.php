<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CatgoryController;
use App\Http\Controllers\Admin\ProductController;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\CouponController;

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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//customers Route
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::apiResource('admin/product',ProductController::class);
    Route::apiResource('admin/category',CategoryController::class);
    Route::get('/users', [AuthController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::delete('/delete', [AuthController::class, 'deleteUser']);
    Route::apiResource('carts',CartController::class);
    Route::apiResource('orders',OrderController::class);
    Route::apiResource('coupon',CouponController::class);

});



