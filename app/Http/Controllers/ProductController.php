<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Product;
use App\Models\Thumbnail;
use Hamcrest\Arrays\IsArray;
use Hamcrest\Type\IsObject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::latest()->get();
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


    //Lọc sản phẩm
    function handleFilter(Request $request)
    {
        $slug = $request->input('category', null);
        $price = $request->input('price', []);
        $rating = $request->input('rating', null);
        $page = $request->input('page', 1); // Trang hiện tại, mặc định là 1
        $perPage = $request->input('per_page', 10); // Số sản phẩm trên mỗi trang, mặc định là 10

        try {
            $query = Product::select(
                'id',
                'name',
                'average_rating',
                'description',
                'price',
                'imageUrl',
                'discount',
                'price',
                'category_id',
                DB::raw('price * (1 - discount / 100) as current_Price')
            );

            if ($slug !== null) {
                $query = Product::whereHas('category', function ($categoryQuery) use ($slug) {
                    $categoryQuery->where('id', $slug)
                        ->orWhere('slug', $slug);
                })->select(
                    'id',
                    'name',
                    'average_rating',
                    'description',
                    'price',
                    'imageUrl',
                    'discount',
                    'category_id',
                    DB::raw('price * (1 - discount / 100) as current_price')
                );
            }

            if ($rating !== null) {
                $query->where('average_rating', $rating);
            }

            if (!empty($price) && count($price) === 2) {

                $query->whereBetween(DB::raw('price * (1 - discount / 100)'), $price);
            }

            // Sử dụng phân trang để giới hạn kết quả
            $perPage = $request->input('per_page', 9);
            $page = $request->input('page', 1);

            $products = $query->with(['thumbnails', 'category'])
                ->paginate($perPage, ['*'], 'page', $page);

            if ($products->isEmpty()) {
                return response()->json(['message' => 'Không có sản phẩm nào'], 404);
            }

            return response()->json(['products' => $products], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Không tìm thấy category'], 404);
        }
    }
    //Tìm kiếm sản phẩm theo tên
    function searchProduct(Request $request)
    {
        $name = $request->input('name');
        $products = Product::where('name', 'like', '%' . $name . '%')->get();
        if ($products->isEmpty()) {
            return response()->json([
                "message" => "Không có sản phẩm nào được tìm thấy.",
            ]);
        }
        return response()->json([
            "products" => $products
        ], 200);
        
    }
    function admin_search_product(Request $request)
    {
        $name = $request->input('name');
        $products = Product::where('name', 'like', '%' . $name . '%')->paginate(10);
        if ($products->isEmpty()) {
            return response()->json([
                "message" => "Không có sản phẩm nào được tìm thấy.",
            ]);
        }
        return response()->json([
            "products" => $products
        ], 200);
        
    }
    //Product quick view
    function quickView($id)
    {
        $product = Product::with(['thumbnails', 'category'])
            ->find($id)->only(['id', 'name', 'average_rating', 'description', 'price', 'imageUrl', 'discount', 'thumbnails', 'category']);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'product' => $product,
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

        $categories_id = category::pluck('id');
        $rules = [
            'name' => 'required|string|max:200',
            'category_id' => [
                'required',
                Rule::in($categories_id),
            ],
            'imageUrl' => 'required|url',
            'quantity' => 'required|numeric',
            // 'average_rating' => [
            //     'nullable',
            //     Rule::in([1, 2, 3, 4, 5]),
            // ],
            'discount' => [
                'nullable',
                Rule::in(range(0, 100)),
            ],
            'type' => 'required|string',
            'weight' => 'required|numeric',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'thumbnails'=>'required|array'
        ];
        $messages = [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name must not exceed :max characters.',
            'category_id.required' => 'The category field is required.',
            'category_id.in' => 'Invalid category selected.',
            'imageUrl.required' => 'The image URL field is required.',
            'imageUrl.url' => 'Please enter a valid URL for the image.',
            'quantity.required' => 'The quantity field is required.',
            'quantity.numeric' => 'The quantity must be a number.',
            // 'average_rating.required' => 'The average rating field is required.',
            // 'average_rating.in' => 'Invalid average rating selected.',
            'discount.nullable' => 'The discount must be nullable.',
            'discount.in' => 'Invalid discount value. It must be between 1 and 100.',
            'weight.required' => 'The weight field is required.',
            'weight.numeric' => 'The weight must be a number.',
            'description.required' => 'The description field is required.',
            'description.string' => 'The description must be a string.',
            'type.required' => 'The type field is required.',
            'type.string' => 'The type must be a string.',
            'price.required' => 'The price field is required.',
            'price.numeric' => 'The price must be a number.',
            'thumbnails.required' => 'The thumbnails field is required.',
            'thumbnails.array' => 'The thumbnails field must be an array.',
        ];

       
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['message' => $errors], 422);
        }

        try {
            $product = new Product;
            $product->fill($request->all());
            $product->save();

            $thumbnailsData = $request->thumbnails;
            
            foreach ($thumbnailsData as $thumbnailUrl) {
                $product->thumbnails()->create([
                    'imageUrl' => $thumbnailUrl,
                ]);
            }

        } catch (\Throwable $th) {
            return response()->json(["errors" => $th->getMessage()], 500);
        }
        return response()->json(["message"=>'thêm sản phẩm thành công']);

        
    }

    /**
     * Display the specified resource.
     */
    public function show($product_id)
    {

        $product = Product::with('thumbnails')->find($product_id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $category_id = $product->category->id;

        $sameProducts = Product::with('category')
            ->where('category_id', $category_id)
            ->where('id', '!=', $product_id)
            ->limit(5)
            ->select('id', 'name', 'imageUrl', 'average_rating', 'description', 'price', 'discount', 'category_id')
            ->get();

        return response()->json([
            'product' => $product,
            // 'category_id'=>$category_id
            "sameProducts" => $sameProducts
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
    public function update(Request $request, $id)
    {
        $categories_id = Category::pluck('id');

    $rules = [
        'name' => 'required|string|max:200',
        'category_id' => [
            'required',
            Rule::in($categories_id),
        ],
        'imageUrl' => 'required|url',
        'quantity' => 'required|numeric',
        // 'average_rating' => [
        //     'required',
        //     Rule::in([1, 2, 3, 4, 5]),
        // ],
        'discount' => [
            'nullable',
            Rule::in(range(1, 100)),
        ],
        'type' => 'required|string',
        'weight' => 'required|numeric',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'thumbnails' => 'required|array',
    ];

    $messages = [
        'name.required' => 'The name field is required.',
        'name.string' => 'The name must be a string.',
        'name.max' => 'The name must not exceed :max characters.',
        'category_id.required' => 'The category field is required.',
        'category_id.in' => 'Invalid category selected.',
        'imageUrl.required' => 'The image URL field is required.',
        'imageUrl.url' => 'Please enter a valid URL for the image.',
        'quantity.required' => 'The quantity field is required.',
        'quantity.numeric' => 'The quantity must be a number.',
        // 'average_rating.required' => 'The average rating field is required.',
        // 'average_rating.in' => 'Invalid average rating selected.',
        'discount.nullable' => 'The discount must be nullable.',
        'discount.in' => 'Invalid discount value. It must be between 1 and 100.',
        'weight.required' => 'The weight field is required.',
        'weight.numeric' => 'The weight must be a number.',
        'description.required' => 'The description field is required.',
        'description.string' => 'The description must be a string.',
        'type.required' => 'The type field is required.',
        'type.string' => 'The type must be a string.',
        'price.required' => 'The price field is required.',
        'price.numeric' => 'The price must be a number.',
        'thumbnails.required' => 'The thumbnails field is required.',
        'thumbnails.array' => 'The thumbnails field must be an array.',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()], 422);
    }

    try {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        $thumbnailsData = $request->thumbnails;
        $product->thumbnails()->delete(); 
        $product->thumbnails()->createMany(array_map(function ($thumbnailUrl) {
            return ['imageUrl' => $thumbnailUrl];
        }, $thumbnailsData));

    } catch (\Throwable $th) {
        return response()->json(["errors" => $th->getMessage()], 500);
    }

    return response()->json( ['message' => 'Cập nhật sản phẩm thành công']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the product by ID
            $product = Product::findOrFail($id);
    
            // Delete the product
            $product->delete();
    
        } catch (\Throwable $th) {
            return response()->json(["errors" => $th->getMessage()], 500);
        }
    
        return response()->json(['message' => 'Xóa sản phẩm thành công']);
    
    }

    function admin_show_products() {
        $products = Product::latest()->paginate(10);
        if ($products->isEmpty()) {
            return response()->json([
                "message" => "Không có sản phẩm nào được tìm thấy.",
            ], 404);
        }
        return response()->json([
            "products" => $products
        ], 200);
    }
}
