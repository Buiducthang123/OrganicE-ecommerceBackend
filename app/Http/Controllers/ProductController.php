<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        if ($products) {
            return response()->json([
                "products" => $products
            ], 200);
        }
        return response()->json([
            "message" => "Không có sản phẩm nào được tìm thấy.",
        ], 404);
    }

    //Sản phẩm nổi bật
    function featuredProducts()
    {
        // // Lấy 4 sản phẩm có discount và average_rating lớn nhất
        $products = Product::orderBy('discount', 'desc')
            ->orderBy('average_rating', 'desc')
            ->limit(4)
            ->get();
        if ($products) {
            return response()->json([
                "products" => $products
            ], 200);
        }
        return response()->json([
            "message" => "Không có sản phẩm nào được tìm thấy.",
        ], 404);
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
    public function show(Product $product)
    {
        //
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
