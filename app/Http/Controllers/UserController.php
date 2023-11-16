<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
        $user = Auth::user();
        
        if ($user) {
            try {
                $validatedData = $request->validate([
                    'name' => 'required|string',
                    'email' => 'required|email',
                    'phone_number' => 'required|string',
                    'avata' => 'url', 
                ]);
                $user->update($validatedData);
                return response()->json(['message' => 'User updated successfully']);
            } catch (ValidationException $e) {
                return response()->json(['error' => $e->errors()], 422);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to update user.'], 500);
            }
        }
        return response()->json(["message" => "Chưa đăng nhập", 401]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
    public function changePassword(Request $request) {
        $user = auth()->user();
        if($user){
           $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8',
           ]);
           if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
            }
            if (Hash::check($request->current_password, $user->password)) {
                // Update the user's password with the new one
                $user->update(['password' => bcrypt($request->new_password)]);
        
                return response()->json(['message' => 'Password changed successfully']);
            }
            return response()->json(['error' => 'Current password is incorrect'], 422);

        }
        return response()->json(['message'=> 'Chưa đăng nhập'],401);
    }
}
