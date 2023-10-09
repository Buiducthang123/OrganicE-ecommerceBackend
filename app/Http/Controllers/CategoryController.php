<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = category::all();
        if ($categories->isEmpty()) {
            return response()->json([
                "message" => "Không có danh mục nào được tìm thấy.",
            ], 404);
        }
        return response()->json([
            "categories" => $categories
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
     * //Hiển thị sản phẩm theo danh mục
     */
    public function show($slug)
    {
        //
        $category = Category::where('id', $slug)
        ->orWhere('slug', $slug)
        ->firstOrFail();
        $products = $category->products;
        if ($products->isEmpty()) {
            return response()->json([
                "message" => "Không có sản phẩm nào được tìm thấy.",
            ], 404);
        }
        return response()->json([
            "products" => $products
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(category $category)
    {
        //
    }
}
