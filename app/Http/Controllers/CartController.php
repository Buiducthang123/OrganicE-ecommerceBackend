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
            $cartItems = $user->cart->cartItems;
            
            return response()->json(['cart'=>$cartItems]);
        } else {
            // Người dùng chưa đăng nhập
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Bạn cần đăng nhập để truy cập tài nguyên này.',
            ], 401);
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
