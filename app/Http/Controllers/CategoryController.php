<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = category::all()->latest();
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
        $rules = [
            'name' => 'required |string|max:200',
            // 'slug'=>'required |string|max:200|unique:categories.slug',
            'image' => 'required |url'
        ];
        $messages = [
            'name.required' => 'The name field is required.',
            'name.string'   => 'The name must be a string.',
            'name.max'      => 'The name may not be greater than 200 characters.',
            // 'slug.required' => 'The slug field is required.',
            // 'slug.string'   => 'The slug must be a string.',
            // 'slug.max'      => 'The slug may not be greater than 200 characters.',
            // 'slug.unique'   => 'The slug has already been taken.',
            'image.required' => 'The image field is required.',
            'image.url'     => 'The image must be a valid URL.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $category = new category;
        $category->fill($request->all());
        try {
            // Try saving the category
            if ($category->save()) {
                return response()->json(['message' => 'Thêm mới category thành công']);
            }
        } catch (QueryException $e) {
            // Handle SQL error and print the error message
            $sqlError = $e->getMessage();
            return response()->json(['Thêm mới không thành công errors'], 500);
        }
        $saveErrors = $category->getErrors()->all();
        return response()->json(['Thêm mới không thành công errors'], 500);
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
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required |string|max:200',
            'image' => 'required |url'
        ];
        $messages = [
            'name.required' => 'The name field is required.',
            'name.string'   => 'The name must be a string.',
            'name.max'      => 'The name may not be greater than 200 characters.',
            'image.required' => 'The image field is required.',
            'image.url'     => 'The image must be a valid URL.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $category = category::find($id);
        
        try {
            $category->update($request->all());
            return response()->json(["message"=>'Cập nhật thành công']);
        } catch (\Throwable $th) {
            return response()->json(['message'=>"Cập nhật không thành công"],500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $category = category::find($id);
        
        try {
            $category->delete();
            return response()->json(["message"=>'Xóa thành công']);
        } catch (\Throwable $th) {
            return response()->json(['message'=>"Xóa không thành công"],500);
        }
    }
    public function searchCategories(Request $request) {
        $name = $request->name;
        $categories = Category::where('name', 'like', '%' . $name . '%');
        if ($categories->isEmpty()) {
            return response()->json([
                "message" => "Không có danh mục nào được tìm thấy.",
            ], 404);
        }
        return response()->json([
            "categories" => $categories
        ], 200);
    }
    
}
