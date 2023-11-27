<?php

namespace App\Http\Controllers;

use App\Models\BillingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillingAddressController extends Controller
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
    public function show(BillingAddress $billingAddress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BillingAddress $billingAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $user = auth()->user();
        if($user){
            $validator = Validator::make($request->all(), [
                "name"=>"required|string",
                "company_name"=>"string",
                "address"=>"required|string",
                "phone"=>["required","string",],
                "email"=>"required|email"
            ]);
            if($validator->fails()){
                return response()->json(['error' => $validator->errors()], 422);
            }
            $billingAddress = $user->billing_address()->update($request->all());
            return response()->json(['Update thành công'=> true],200);
        }
        return response()->json(['message'=> 'Chưa đăng nhập'],401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BillingAddress $billingAddress)
    {
        //
    }

    
}
