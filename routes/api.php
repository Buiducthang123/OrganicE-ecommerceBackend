<?php

use App\Http\Controllers\Admin\ManageUserController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BillingAddressController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishListController;

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
Route::resource('/categories', CategoryController::class)->only(['index', 'show']);
//Products api---------------------------------------------------------------------------------

Route::resource('/product', ProductController::class)->except(['update','store','edit','create']);
Route::prefix('products')->group(function () {

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
Route::resource('cart', CartController::class)->middleware('auth:sanctum');
Route::prefix('carts')->middleware('auth:sanctum')->group(function () {
    // Tuyến đường cho giỏ hàng
    // Tuyến đường cho thông tin nhanh giỏ hàng
    Route::get("quick_infor", [CartController::class, 'quickInfor']);
});


//Danh sách sản phẩm yêu thích
Route::resource('wish_list', WishListController::class)->middleware('auth:sanctum');
Route::put('/ahahah/{id}', [ProductController::class, 'testFunc'])->middleware('auth:sanctum')->middleware('roleMiddleware');


//Route Blog
Route::resource('/blog', BlogController::class, [
    'except' => ['index', 'show']
])->middleware(['auth:sanctum', 'roleMiddleware']);
Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{id}', [BlogController::class, 'show']);
Route::get('/blog/comments/{blog_id}', [BlogController::class, 'showComments']);
Route::resource('/comment', CommentController::class)->middleware('auth:sanctum');
//hóa đơn
Route::resource("/order_detail", OrderDetailController::class)->middleware('auth:sanctum');
Route::get("/order_filter_status", [OrderDetailController::class, 'filter_status_order'])->middleware(['auth:sanctum']);

//User API
Route::resource('/user', UserController::class)->except('update')->middleware(['auth:sanctum']);
Route::put("/user/update", [UserController::class, "update"])->middleware(['auth:sanctum']);

//BillingAddress
// Route::resource('/billingAddress', BillingAddressController::class)->middleware(['auth:sanctum']);
Route::put("/billing_address/update", [BillingAddressController::class, "update"])->middleware(['auth:sanctum']);
//Change Password
Route::put('/user/change_password', [UserController::class, 'changePassword'])->middleware(['auth:sanctum']);

//---------------------------------------------------------------------------------
//ADMIN
Route::prefix("admin")->middleware(['auth:sanctum',"roleMiddleware"])->group(function () {
    // Quản lý users
    Route::prefix("user")->group(function () {
        //Xem danh sách user
        Route::get("/show_users", [ManageUserController::class, "show_users"]);
        //Xem chi tiết user
        Route::get("/show_user/{user_id}", [ManageUserController::class, "show_user"]);
        //Thêm user
        Route::post("/addUser", [ManageUserController::class, "add_user"]);
        //Xóa user
        Route::delete("/delete_user/{user_id}", [ManageUserController::class, "delete_user"]);
        //ban user
        Route::put("/ban_user",[ManageUserController::class, "ban_user"]);
        //grant_permissions
        Route::put('/grant_permissions',[ManageUserController::class, "grant_permissions"]);
        //tìm kiếm users
        Route::get("/search_users",[ManageUserController::class, "search_users"]);
    });
    //Quản lý categories
    Route::prefix('category')->group(function() {
        //thêm cate
        Route::post("/",[CategoryController::class,'store']);
        //sửa cate
        Route::put("/{id}",[CategoryController::class,'update']);
        //xem chi tiết cate
        Route::get("details/{id}",[CategoryController::class,'edit']);
        //xóa
        Route::delete("/{id}",[CategoryController::class,'destroy']);
        //tìm kiếm
        Route::get("/search_categories",[CategoryController::class,'searchCategories']);
    });

    Route::prefix('product')->group(function () {
       Route::post("/",[ProductController::class,'store']); 
       Route::put("/{id}",[ProductController::class,'update']); 
       Route::delete("/{id}",[ProductController::class,'destroy']); 
       Route::get('/show_products',[ProductController::class,'admin_show_products']);
       //tìm kiếm products admin
       Route::get('/admin_search_product',[ProductController::class,'admin_search_product']);
    });
    //QUản lý đơn hàng
    Route::prefix('order')->group(function () {
        //Xem đơn hàng chưa duyệt
        Route::get('/',[OrderManagementController::class,'order_list']);
        //Duyệt đơn hàng
        Route::patch('/approve_orders/{order_detail}',[OrderManagementController::class,"approve_orders"]);
        //Hủy đơn hàng
        Route::patch('/cancel_order/{order_detail}',[OrderManagementController::class,'cancel_order']);
        //Hiển thị chi tiết order
        Route::get('view_order_details/{order_detail}',[OrderManagementController::class,'view_order_details']);
        //Lọc status 
        Route::get('filter_status',[OrderManagementController::class,'filter_status']);
    });
});
