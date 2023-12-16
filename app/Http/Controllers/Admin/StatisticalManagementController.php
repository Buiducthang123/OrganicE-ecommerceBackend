<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class StatisticalManagementController extends Controller
{
    //
    public function index(Request $request)
    {
        $totalCustomer = User::count();

        $order_status = [
            "0" => "wait_for_confirm",
            "1" => "order_received",
            "2" => "processing",
            "3" => "on_the_way",
            "4" => "delivered",
            "5" => "canceled",
        ];
        $totalOrder = ["total" => OrderDetail::count()];

        foreach ($order_status as $key => $label) {
            $totalOrder[$label] = OrderDetail::where('approval_status', $key)->count();
        }
        //Tổng sản phẩm
        $total_products = Product::count();

        $categorySlug = Category::pluck('slug');
        $ct = [];
        foreach ($categorySlug as  $value) {
            $category = Category::where('slug', $value)->first();

            $ct[$category->name] = $category->gross_product;
        }
        $total_category = category::count();

        //Doanh thu
        $year = $request->year?$request->year:null;
        $year = $year ?? "2023";
        //tổng doanh thu
        $totalRevenue = OrderDetail::where('approval_status', '4')->sum('total_price');
       //doanh thu năm
        $totalRevenue_Y =  OrderDetail::where('approval_status', '4')->whereYear('created_at', $year)->sum('total_price');
        
        //doanh thu theo từng tháng trong năm
        $totalRevenueDetails = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $totalRevenueYear =  OrderDetail::where('approval_status', '4')->whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('total_price');
            $totalRevenueDetails[$year][$month] = $totalRevenueYear;
        }
        $responseData = [
            'totalCustomer' => $totalCustomer,
            'totalOrder' => $totalOrder,
            'totalProducts' => $total_products,
            'totalCategory' => [
                'total' => $total_category,
                'products_in_category' => $ct
            ],
            'revenue' => [
                'totalRevenue'=>$totalRevenue,
                'totalYear'=>$totalRevenue_Y,
                'details'=>$totalRevenueDetails
            ]
        ];

        return response()->json($responseData);
    }
}
