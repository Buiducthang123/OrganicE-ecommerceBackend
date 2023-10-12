<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
       
        if (Auth::user()) {
            # code...
            $customMessages = [
                "product_id.required"=>"Product_id Không được bỏ trống",
                "quantity.required"=>"Quantity không được bỏ trống"
            ];
            $validator = Validator::make($request->all(), [
                "product_id"=>"required",
                "quantity"=>"required"
            ], $customMessages);    
            
            if($validator->fails()){
                $errors = $validator->errors();
                return response()->json(["errors" => $errors], 422);
            }

            $user = Auth::user();  // Lấy đối tượng người dùng hiện tại
              // Lấy user_id từ đối tượng người dùng
            $cart_id = $user->cart->id;

            $existingCartItem = CartItem::where('cart_id', $cart_id)
                                ->where('product_id', $request->product_id)
                                ->first();
            if(!$existingCartItem)
            {
                $cartItem = new CartItem();
                $cartItem->cart_id = $cart_id;
                $cartItem->product_id = $request->product_id;
                $cartItem->quantity = $request->quantity;
                $cartItem->save();
                
            }
            else{
                $existingCartItem->quantity += $request->quantity;
                $existingCartItem->save();
            }
           
            return response()->json(["message"=>"Thêm vào giỏ hàng thành công"]);
        }
        return response()->json(['message'=>"Người dùng chưa đăng nhập",401]);
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        //
    }
}
