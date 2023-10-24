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
                "product_id.required" => "Product_id Không được bỏ trống",
                "quantity.required" => "Quantity không được bỏ trống"
            ];
            $validator = Validator::make($request->all(), [
                "product_id" => "required",
                "quantity" => "required"
            ], $customMessages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(["errors" => $errors], 422);
            }

            $user = Auth::user();  // Lấy đối tượng người dùng hiện tại
            // Lấy user_id từ đối tượng người dùng
            $cart_id = $user->cart->id;

            $existingCartItem = CartItem::where('cart_id', $cart_id)
                ->where('product_id', $request->product_id)
                ->first();
            if (!$existingCartItem) {
                $cartItem = new CartItem();
                $cartItem->cart_id = $cart_id;
                $cartItem->product_id = $request->product_id;
                $cartItem->quantity = $request->quantity;
                $cartItem->save();
            } else {
                $existingCartItem->quantity += $request->quantity;
                $existingCartItem->save();
            }

            return response()->json(["message" => "Thêm vào giỏ hàng thành công"]);
        }
        return response()->json(['message' => "Người dùng chưa đăng nhập", 401]);
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
    public function update(Request $request, $product_id)
    {
      
       
        if (Auth::check()) {
            $user = Auth::user();  
            $cart = $user->cart;
            if (!$cart) {
                return response()->json(["message" => "Không tìm thấy giỏ hàng cho người dùng này"], 404);
            }

            $existingCartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product_id)
                ->first();

            if ($existingCartItem) {
                $existingCartItem->update([
                    'quantity' => $request->quantity
                ]);

                return response()->json(["message" => "Cập nhật giỏ hàng thành công"]);
            } else {
                return response()->json(["message" => "Không tìm thấy sản phẩm trong giỏ hàng"]);
            }
        } else {
            return response()->json(["message" => "Người dùng chưa đăng nhập"], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product_id)
    {
        //
        if (Auth::check()) {
            $user = Auth::user();
            $cart_id = $user->cart->id;
            if ($cart_id) {
                $cartItem = CartItem::where('cart_id', $cart_id)
                    ->where('product_id', $product_id)
                    ->first();
                if ($cartItem) {
                    $cartItem->delete();
                    return response()->json(['message' => 'Sản phẩm đã được xóa khỏi giỏ hàng']);
                } else {
                    return response()->json(['message' => 'Sản phẩm không tồn tại trong giỏ hàng']);
                }
            } else {
                return response()->json(['message' => 'Không tìm thấy giỏ hàng cho người dùng này']);
            }
        }

        return response()->json(['message' => 'Bạn chưa đăng nhập'], 401);
    }
}
