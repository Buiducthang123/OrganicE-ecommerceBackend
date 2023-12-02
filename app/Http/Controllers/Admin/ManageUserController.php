<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\ElseIf_;

class ManageUserController extends Controller
{
    //

    function add_user(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'phone_number' => 'required|size:10',
        ];

        $messages = [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',

            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',

            'password.required' => 'The password field is required.',

            'phone_number.required' => 'The phone number field is required.',
            'phone_number.size' => 'The phone number must be 10 digits.',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = new User();
        $user->fill($request->all());
        $user->save();
        // return response()->json($request->all());
        return response()->json(['message' => "Thêm mới thành côngg"]);
    }

    function show_users()
    {
        $users =  User::latest()->paginate(10, ["id", "email", "name", "phone_number", "avata", "role_id", "status"]);
        if ($users) {
            return response()->json($users);
        }
        return response()->json(["message" => 'Không có người dùng nào']);
    }

    function show_user($user_id)
    {
        $user = User::find($user_id);
    
        if ($user) {
            $user->load([
                'billing_address' => function ($query) {
                    $query->select('billing_addresses.user_id', 'billing_addresses.name', 'billing_addresses.phone', 'company_name', 'billing_addresses.email', 'billing_addresses.address');
                },
                
            ]);
    
            $paginatedOrderDetails = $user->order_detail()->paginate(10);
    
            // Transform the paginated order details
            $transformedOrderDetails = $paginatedOrderDetails->getCollection()->transform(function ($orderDetail) {
                return [
                    'id' => $orderDetail->id,
                    'user_id' => $orderDetail->user_id,
                    'products_order' => $orderDetail->decoded_products_order,
                    'total_price' => $orderDetail->total_price,
                    'address_shipping' => $orderDetail->address_shipping,
                    'payment_method' => $orderDetail->payment_method,
                    'approval_status' => $orderDetail->approval_status,
                    'phone_number' => $orderDetail->phone_number,
                    'name' => $orderDetail->name,
                    'email' => $orderDetail->email,
                    'note' => $orderDetail->note,
                    'created_at' => $orderDetail->created_at,
                    'updated_at' => $orderDetail->updated_at,
                ];
            });
    
            // Replace the original order details with the transformed and paginated version
            $paginatedOrderDetails->setCollection($transformedOrderDetails);
    
            // Return the user and paginated order details in the response
            return response()->json([
                'user' => $user,
                'order_details' => $paginatedOrderDetails,
            ]);
        }
    
        return response()->json(['Message' => 'Không tồn tại người dùng này']);
    }

    function delete_user($user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            if ($user->id != Auth::id()) {
                $user->delete();
                return response()->json(['message' => "User đã bị xóa"]);
            }
            return response()->json(['Message' => 'Không xóa được chính mình'], 400);
        }
        return response()->json(['Message' => 'Không tồn tại người dùng này']);
    }

    function ban_user(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            if ($user->permission != 2) {
                if ($user->status == 0) {
                    $user->status = 1;
                    $user->save();
                    return response()->json(['message' => "Thằng $user->name này đã bị cấm"]);
                }
                $user->status = 0;
                $user->save();
                return response()->json(['message' => "Thằng  $user->name này hết bị cấm"]);
            }
            return response()->json(['message' => "Không ban được thằng Admin :)))"], 400);
        }

        return response()->json(['Message' => 'Không tồn tại người dùng này']);
    }
    function grant_permissions(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            // return response()->json($user->id == $request->user_id && $user->permission != 2);
            if ($user->permission == 2) {
                if (Auth::id() == $request->user_id) {
                    return response()->json(['message' => "Mày không có quyền hủy chính mình "], 400);
                }
                $user->role_id = 1;
                $user->save();
                return response()->json(['message' => "Thằng $user->name này không còn là Admin"]);
            }

            $user->role_id = 2;
            $user->save();
            return response()->json(['message' => "Thằng $user->name này đã thành Admin"]);
        }

        return response()->json(['Message' => 'Không tồn tại người dùng này']);
    }

    function search_users(Request $request)
    {

        // return $request->name();
        $name = $request->name ? $request->name : '';

        $users = User::where('name', 'like', '%' . $name . '%')->paginate(10, ["id", "email", "name", "phone_number", "avata", "role_id", "status"]);

        if ($users) {
            return response()->json($users);
        }
        return response()->json(["message" => 'Không có người dùng nào']);
    }
}
