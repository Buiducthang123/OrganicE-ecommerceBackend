<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Hamcrest\Type\IsObject;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::all();
        if ($products->isEmpty()) {
            return response()->json([
                "message" => "Không có sản phẩm nào được tìm thấy.",
            ], 404);
        }
        return response()->json([
            "products" => $products
        ], 200);
    }

    //Sản phẩm nổi bật
    function featuredProducts()
    {
        // // Lấy 4 sản phẩm có discount và average_rating lớn nhất
        $products = Product::orderBy('discount', 'desc')
            ->orderBy('average_rating', 'desc')
            ->limit(4)
            ->get();
        if ($products->isEmpty()) {
            return response()->json([
                "message" => "Không có sản phẩm nào được tìm thấy.",
            ], 404);
        }
        return response()->json([
            "products" => $products
        ], 200);
    }


    //sản phẩm bán chạy nhất
    function bestSellerProducts()
    {
        $bestSellers = Product::orderBy('sales_count', 'desc')
            ->limit(4)
            ->get();
        if ($bestSellers->isEmpty()) {
            return response()->json([
                "message" => "Không có sản phẩm nào được tìm thấy.",
            ], 404);
        }
        return response()->json([
            "bestSellers" => $bestSellers
        ], 200);
    }

    // hiển thị 3 sản phẩm hot deals
    public function hotDeals()
    {
       
        $products = Product::orderBy('discount', 'desc')->latest()->limit(4)->get();
    
        if ($products->isEmpty()) {
            return response()->json([
                "message" => "Không có sản phẩm nào được tìm thấy.",
            ], 404);
        }
    
        return response()->json([
            "products" => $products,
        ], 200);
    }
    //Hiển thị 3 sản phẩm top rated
    function topRated() {
        $products = Product::where('average_rating','>=',3.5)->orderBy('sales_count', 'desc')->limit(4)->get();
    
        if ($products->isEmpty()) {
            return response()->json([
                "message" => "Không có sản phẩm nào được tìm thấy.",
            ], 404);
        }
    
        return response()->json([
            "products" => $products,
        ], 200);
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
    public function show($id)
    {
        $product = Product::with('thumbnails')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        return response()->json([
            'product' => $product,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
