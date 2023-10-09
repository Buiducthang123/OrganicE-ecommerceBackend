<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Product;
use Hamcrest\Type\IsObject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $products = Product::orderBy('average_rating', 'desc')
            ->orderBy('discount', 'desc')->where('sales_count', '>', '500')
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
            ->limit(12)
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
    function topRated()
    {
        $products = Product::where('average_rating', '>=', 3.5)->orderBy('sales_count', 'desc')->limit(4)->get();

        if ($products->isEmpty()) {
            return response()->json([
                "message" => "Không có sản phẩm nào được tìm thấy.",
            ], 404);
        }

        return response()->json([
            "products" => $products,
        ], 200);
    }


    //filter func
    function handleFilter(Request $request)
    {
        $request->validate([
            "price" => "array|size:2",
            "price.*" => "numeric", // Xác thực rằng các phần tử trong mảng là số
            "rating" => "between:1,5"
        ]);
        $slug = $request->input('category', [1]);
        $price = $request->input('price', []);
        $rating = $request->input('rating');
        $page = $request->input('page', 1); // Trang hiện tại, mặc định là 1
        $perPage = $request->input('per_page', 10); // Số sản phẩm trên mỗi trang, mặc định là 10


        try {
            $query = Category::where('id', $slug)
            ->orWhere('slug', $slug)
            ->firstOrFail()->products();

            if ($rating !== null) {
                $query->where('average_rating', $rating);
            }

            if ($price !== null) {
                $query->whereBetween('price', $price);
            }

            $products = $query->paginate($perPage, ['*'], 'page', $page);

            if ($products->isEmpty()) {
                return response()->json(['message' => "Không có sản phẩm nào"], 404);
            }

            return response()->json(['products' => $products], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Không tìm thấy category'], 404);
        }
    }

    function testFunc(Request $request)
    {
        return response()->json($request);
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
        ], 200);
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
