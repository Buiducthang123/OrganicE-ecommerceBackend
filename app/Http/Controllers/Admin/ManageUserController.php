<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManageUserController extends Controller
{
    //

    function add_user(Request $request) {
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

        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $user = new User();
        $user->fill($request->all());
        $user->save();
        // return response()->json($request->all());
        return response()->json(['message'=>"Thêm mới thành côngg"]);

    }

    function show_users() {
        $users =  User::paginate(10,["id","name","phone_number","avata","role_id","status"]);
        if($users){
            return response()->json($users);
        }
    }

    function show_user($user_id) {
        $user = User::find($user_id);
        if($user){
            $user->load(['billing_address' => function ($query) {
                $query->select('billing_addresses.user_id','billing_addresses.first_name', 'billing_addresses.last_name', 'billing_addresses.address');
            }]);
            return response()->json($user);
        }
        return response()->json(['Message'=>'Không tồn tại người dùng này']);
    }

    function delete_user($user_id) {
        $user = User::find($user_id);
        if($user){
            $user->delete();
            return response()->json(['message'=>"User đã bị xóa"]);
        }
        return response()->json(['Message'=>'Không tồn tại người dùng này']);
    }
}
