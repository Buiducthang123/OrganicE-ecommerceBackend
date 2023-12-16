<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StatisticalManagementController extends Controller
{
    //
    public function index() {
        $totalCustomer = User::count(); 
        return response()->json($totalCustomer);
    }
}
