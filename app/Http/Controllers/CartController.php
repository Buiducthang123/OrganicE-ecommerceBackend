<?php

namespace App\Http\Controllers;

use App\Models\Cart;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (Auth::check()) {
            // Người dùng đã đăng nhập
            $user = Auth::user();
            $cartItems = $user->cart->products;
            if(count($cartItems) > 0) {
                return response()->json(['cart' => $cartItems]);
            }
            return response()->json(['message'=> ["Không có sản phẩm nào trong giỏ hàng"]]);

        } else {
            // Người dùng chưa đăng nhập
            return response()->json([
                'message' => 'Bạn cần đăng nhập để truy cập tài nguyên này.',
            ], 401);
        }
    }

    //Hiển thị thông tin nhanh của giỏ hàng (tên/ ảnh/ số lượng/ giá)
    function quickInfor()
    {
        if (Auth::check()) {

            $cart_info = Auth::user()->cart;
           
            $products = $cart_info->products()->select('name','price','imageUrl','weight','discount')->get();

            return response()->json($products);
        }
        return response()->json(["message" => "Chưa đăng nhập"], 401);
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
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
