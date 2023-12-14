<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
            $orderDetails = $user->order_detail()->latest()->get();

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
                    "name"=>$value->name,
                    "phone_number"=>$value->phone_number,
                    "email"=>$value->email,
                    'note' => $value->note,
                    'created_at' => $value->created_at,
                    'updated_at' => $value->updated_at,
                ];

                $formattedOrders[] = $formattedOrder;
            }
            $perPage = 10;
            // Tổng số mục (đơn hàng) trong mảng $formattedOrders
            $total = count($formattedOrders);
            $page = request()->get('page', 1);
            $slicedData = array_slice($formattedOrders, ($page - 1) * $perPage, $perPage);
            $paginator = new LengthAwarePaginator(
                $slicedData, // Dữ liệu cho trang hiện tại
                $total,      // Tổng số mục (đơn hàng) trong mảng
                $perPage,    // Số lượng mục trên mỗi trang
                $page,       // Trang hiện tại
                ['path' => request()->url()]
            );
            $paginatorArray = $paginator->toArray();
            return response()->json($paginatorArray);
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
                'phone_number'=>'required|size:10|string',
                'email'=>"required|email",
                'name'=>"required|string",
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
            $order->email = $request->email;
            $order->name= $request->name;
            $order->phone_number = $request->phone_number;
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
                "phone_number"=>$orderDetail->phone_number,
                "name"=>$orderDetail->name,
                "email"=>$orderDetail->email,
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
    function filter_status_order(Request $request)
    {
        if (Auth::check()) {
            $approval_status = in_array($request->approval_status, [0, 1, 2, 3, 4, 5]) ? $request->approval_status : 0;
            $order_list = OrderDetail::where('user_id', Auth::id())->where('approval_status', $approval_status)->paginate(10, ['id', 'created_at', 'user_id', 'total_price', 'approval_status']);
            
            if ($order_list->isEmpty()) {
                return response()->json(['message' => "Không có đơn hàng nào"]);
            }
        
            return response()->json($order_list);
        }
        
        return response()->json(["message"=>"Bạn chưa đăng nhập"], 401);
    }

    //Hủy đơn hàng cho user
    function cancel_order($id) {
        if(Auth::check()){
            $order = OrderDetail::find($id);
        }
        return response()->json(["message"=>"Mày đăng nhập điii"]);
    }
}
