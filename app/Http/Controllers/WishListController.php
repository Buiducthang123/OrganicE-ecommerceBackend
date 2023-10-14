<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\wishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            // $wishList = $user->productsWishList()->select([ 'name', 'price'])->paginate(10);
            // $wishList = $user->load(['productsWishList' => function($query){
            //     $query->select([ 'name', 'price']);
            // }]);
            $wishList = $user->productsWishList()->paginate(8, ['name', 'price', 'imageUrl']);

            if ($wishList->count() > 0) {
                // Trả về dữ liệu phân trang dưới dạng JSON
                return response()->json(['wishList' => $wishList]);
            } else {
                return response()->json(['message' => 'Danh sách yêu thích trống'], 404);
            }
        } else {
            return response()->json(['message' => 'Bạn chưa đăng nhập'], 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(wishList $wishList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(wishList $wishList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, wishList $wishList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product_id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $product = $user->productswishList()->where('product_id', $product_id)->first();
            if ($product) {
                $user->productswishList()->detach($product); // Xóa sản phẩm khỏi danh sách yêu thích
                return response()->json(['message' => 'Xóa sản phẩm khỏi danh sách yêu thích thành công']);
            } else {
                return response()->json(['message' => 'Sản phẩm không tồn tại trong danh sách yêu thích'], 404);
            }
        }
        return response()->json(['message' => 'Bạn chưa đăng nhập'], 401);
    }
}
