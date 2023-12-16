<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $blogs = Blog::with('category')->orderBy("created_at", "desc")->paginate(10);
        return response()->json($blogs);
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
        $categories_id = Category::pluck("id")->toArray();
        $message = [
            'title.required' => "Tiêu đề blog không được bỏ trống",
            "title.max" => "Độ dài tiêu đề không vượt quá 100 ký tự",
            "category_id.required" => "Category không được để trống",
            "category_id.in_array" => "Không tồn tại category",
            "image.required" => "Hình ảnh chính không được bỏ trống",
            "content.required" => "Nội dung blog không được để trống",
            "content.min" => "Nội dung blog quá ngắn, bắt buộc > 100 ký tự"
        ];

        $validator = Validator::make($request->all(), [
            "title" => "required|max:100",
            "category_id" => ["required", function ($attribute, $value, $fail) use ($categories_id) {
                if (!in_array($value, $categories_id)) {
                    $fail("Không tồn tại category");
                }
            }],
            "image" => "required",
            "content" => "required|min:100"
        ], $message);


        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(["message" => $errors], 422);
        }
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->category_id = $request->category_id;
        $blog->content = $request->content;
        $blog->image = $request->image;
        $blog->save();
        if ($blog->id) {
            return response()->json(["message" => "Thêm bài viết thành công"], 200);
        } else {
            return response()->json(["message" => "Thêm bài viết thất bại"], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($blog_id)
    {
        $blog = Blog::with('category')->find($blog_id);
        if($blog){
            return response()->json($blog);
        }
        return response()->json(["message"=> "Blog không tồn tại"],404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $blog  = Blog::find($id);
        if($blog){
            // return response()->json($blog);
            try {
                $blog->update($request->all());
                return response()->json(["message" => "Update successful"]);
            } catch (\Throwable $th) {
                $errors = $th->getMessage();
                return response()->json(["message" => "Could not update: " . $errors]);
            }
        }
        return response()->json(["message"=>"Lỗi khum sửa đc"]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json(["message"=> "Blog không tồn tại"],404);
        }
        try {
            $blog->delete();
            return response()->json(["message"=>"Xóa blog" .$blog->title. "thành công"]);
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return response()->json(["message"=>"Xóa khum thành công ".$error]);
        }
        
    }
    //Hiển thị cmt trong blog
    public function showComments(Request $request, $blog_id){
        $limit = $request->limit?$request->limit:5;
        $count = Blog::find($blog_id)->comments()->count();
        $comments = Blog::find($blog_id)
        ->comments()
        ->select('comments.id as comment_id','users.name', 'users.email', 'users.avata', 'comments.created_at', 'content')
        ->orderBy('comments.created_at', 'desc')
        ->limit($limit)->get();    
        if($comments){
            return response()->json(["comment"=>$comments,"count"=>$count]);
        }
        return response()->json(['message'=> 'Chưa có cmt nào'],404);
    }
    public function search_blog(Request $request) {
        $content_search = $request->content_search?$request->content_search:"";
        $blogs = Blog::with(['category'=>function ($query) {
            $query->select('id','name','slug');
        }])->where("title","like","%{$content_search}%")->paginate(10);
        return response()->json($blogs);
    }
}
