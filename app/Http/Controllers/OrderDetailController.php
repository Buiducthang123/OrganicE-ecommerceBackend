<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            $orderDetails = $user->order_detail;

            if ($orderDetails->isEmpty()) {
                return response()->json(["message" => "Chưa có hóa đơn nào"]);
            }

            $formattedOrders = [];

            foreach ($orderDetails as $value) {
                // Decode the products for each order detail
                $decodedProducts = $value->decoded_products_order;

                // Format the order details for each order
                $formattedOrder = [
                    'id' => $value->id,
                    'user_id' => $value->user_id,
                    'products_order' => $decodedProducts,
                    'total_price' => $value->total_price,
                    'address_shipping' => $value->address_shipping,
                    'payment_method' => $value->payment_method,
                    "approval_status" => $value->approval_status,
                    'note' => $value->note,
                    'created_at' => $value->created_at,
                    'updated_at' => $value->updated_at,
                ];

                $formattedOrders[] = $formattedOrder;
            }

            return response()->json($formattedOrders);
        }

        return response()->json(["error" => "User not logged in"], 401);
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
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $validator = Validator::make($request->all(), [
                'products_order' => 'required|array',
                'total_price' => 'required',
                'address_shipping' => 'required|string',
                'payment_method' => 'required|string',
                'note' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400); // 400 Bad Request
            }


            $products = json_encode($request->products_order);

            $order = new OrderDetail();
            $order->user_id = $user_id;
            $order->products_order = $products;
            $order->total_price = $request->total_price;
            $order->address_shipping = $request->address_shipping;
            $order->payment_method = $request->payment_method;
            $order->note = $request->note;
            $order->save();

            return response()->json(['message' => 'Order created successfully'], 200);
        }

        return response()->json(['error' => 'User not logged in'], 401); // 401 Unauthorized
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderDetail $orderDetail)
    {
        //
        $user = Auth::user();

        if ($user) {
            
            if (!$orderDetail || $orderDetail->user_id !== $user->id) {
                // If order not found or doesn't belong to the authenticated user
                return response()->json(["message" => "Hóa đơn không tồn tại hoặc không thuộc về bạn"], 404);
            }

            $decodedProducts = $orderDetail->decoded_products_order;

            $formattedOrder = [
                'id' => $orderDetail->id,
                'user_id' => $orderDetail->user_id,
                'products_order' => $decodedProducts,
                'total_price' => $orderDetail->total_price,
                'address_shipping' => $orderDetail->address_shipping,
                'payment_method' => $orderDetail->payment_method,
                'approval_status' => $orderDetail->approval_status,
                'note' => $orderDetail->note,
                'created_at' => $orderDetail->created_at,
                'updated_at' => $orderDetail->updated_at,
            ];

            return response()->json($formattedOrder);
        }

        // If the user is not authenticated
        return response()->json(["message" => "Bạn cần đăng nhập để xem hóa đơn"], 401);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderDetail $orderDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderDetail $orderDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderDetail $orderDetail)
    {
        //
        if (Auth::check()) {
            if (Auth::user()->id != $orderDetail->user_id) {
                return response()->json(['error' => 'Lõiii'], 400);
            }
            $orderDetail->delete();
            return response()->json(['message' => 'Đã xóa'], 200);
        }
        return response()->json("Mày đéo đăng nhập à =(", 401);
    }
}
