<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
// use Illuminate\Routing\Router;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//register route
Route::post('/register', [AuthenticationController::class, 'register']);
//Login api
Route::post('/login', [AuthenticationController::class, 'login']);
//Logout api
Route::delete('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
//get currentUser api
Route::get('/getCurrentUser', [AuthenticationController::class, 'getCurrentUser'])->middleware('auth:sanctum');
//categories api---------------------------------------------------------------------------------
Route::resource('/categories', CategoryController::class);
//Products api-----------------------------------------------------------------------------------



Route::prefix('product')->group(function () {
    Route::resource('/', ProductController::class);
    //Sản phẩm nổi bật (featuredProducts)
    Route::get('/featuredProducts', [ProductController::class, 'featuredProducts']);
    //Sản phẩm bán chạy nhất (bestSellerProducts)
    Route::get('/bestSellerProducts', [ProductController::class, 'bestSellerProducts']);
    // Hot deals
    Route::get('/hotDeals', [ProductController::class, 'hotDeals']);
    //Top Rated
    Route::get('/topRated', [ProductController::class, 'topRated']);
    //Lọc sản phẩm
    Route::get('/filterProducts', [ProductController::class, 'handleFilter']);
    //Tìm kiếm sản phẩm
    Route::get('/searchProduct', [ProductController::class, 'searchProduct']);
    //Product quickView
    Route::get('/quickView/{id}', [ProductController::class, 'quickView']);
});

//Review hien thi danh gia cua khach hang ve trang web
Route::resource('/reviews', ReviewController::class);
// ---------------------------------------------------------------------------/
//Thêm sản phẩm vào giỏ hàng
Route::resource('/cartItem', CartItemController::class)->middleware('auth:sanctum');
//
Route::prefix('cart')->middleware('auth:sanctum')->group(function () {
    // Tuyến đường cho giỏ hàng
    Route::resource('/', CartController::class);

    // Tuyến đường cho thông tin nhanh giỏ hàng
    Route::get("quick_infor", [CartController::class, 'quickInfor']);
});
