<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderManagementController extends Controller
{
    //
    function order_list()
    {
        $order_list = OrderDetail::with(['user' => function ($query) {
            $query->select('id', 'name', 'email','phone_number'); 
        }])->select(['id', 'created_at', 'user_id', 'total_price', 'approval_status'])
            ->paginate(10);
        
        return response()->json($order_list);
        
    }
    function approve_orders(OrderDetail $orderDetail)
    {
        // return response()->json("ababab");
        try {
            $approval_status = (int) $orderDetail->approval_status;

            // return response()->json($approval_status);
            switch ($approval_status) {
                case 0:
                    $message = "Đơn hàng đã được duyệt";
                    break;
                case 1:
                    $message = "Đơn hàng đã được duyệt sang trạng thái 2";
                    break;
                case 2:
                    $message = "Đơn hàng đã được duyệt sang trạng thái 3";
                    break;
                case 3:
                    $message = "Đơn hàng đã hoàn thành thành";
                    break;
                case 4:
                    $message = "Đơn hàng đã hoàn thành";
                    return response()->json(["message" => $message]);
            }
            $newApprovalStatus = $approval_status + 1;
            $orderDetail->update(['approval_status' => $newApprovalStatus . '']);
            return response()->json(["message" => $message]);
        } catch (\Throwable $th) {
            $errorMessage = $th->getMessage();
            return response()->json(['error' => $errorMessage], 500);
        }
    }
    function cancel_order(OrderDetail $orderDetail)
    {
        try {
            $orderDetail->update(['approval_status' => "5"]);
            return response()->json(["message" => "Đơn hàng đã bị hủy"]);
        } catch (\Throwable $th) {
            $errorMessage = $th->getMessage();
            return response()->json(['error' => $errorMessage], 500);
        }
    }
    function view_order_details(OrderDetail $orderDetail)
    {
        $orderDetail = $orderDetail->load(['user']);
        return response()->json($orderDetail);
    }
    function filter_status(Request $request)
    {
        $approval_status =  in_array($request->approval_status, [0,1, 2, 3, 4, 5]) ? $request->approval_status : 0;
        $order_list = OrderDetail::where('approval_status', $approval_status)->paginate(10, ['id', 'created_at', 'user_id', 'total_price', 'approval_status']);

        if ($order_list->isEmpty()) {
            return response()->json(['message' => "Không có đơn hàng nào"]);
        }
        return response()->json($order_list);
    }
}
