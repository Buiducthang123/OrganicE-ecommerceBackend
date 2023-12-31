<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(["hiii"] );

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
        $user_id = auth()->user()->id;
        $blog_ids = Blog::pluck('id')->toArray();
        $validator = Validator::make($request->all(), [
            'blog_id' => [
                'required',
                Rule::in($blog_ids),
            ],
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(["errors" => $errors], 422);
        }
        $comment = new Comment();
        $comment->user_id = $user_id;
        $comment->content = $request->content;
        $comment->blog_id = $request->blog_id;
        $comment->save();
        return response()->json(["message" => "Thành công"], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        // //
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        
        if (Auth::check()) {
            $comment = Comment::find($id);  
            
            if (Auth::user()->id == $comment->user_id) {
                $comment->content = $request->content;
                $comment->save();
                return response()->json(["message" => "Thành công"],200);
            }
            return response()->json(["message" => "Bạn không có quyền chỉnh sửa"], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (Auth::check()) {
            if (Auth::user()->id != $comment->user_id) {
                return response()->json(['error' => 'Lõiii'], 400);
            }
            $comment->delete();
            return response()->json(['message' => 'Đã xóa'], 200);
        }
        return response()->json("Mày chưa đăng nhập à =(", 401);
    }
}
