<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PostDetailResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        // return response()->json(['data' => $posts]);
        return PostDetailResource::collection($posts->loadMissing(['Author:id,username', 'Comments:id,post_id,user_id,comments_content']));
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
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
        ]);
        $request['author_id'] = Auth::user()->id;
        $post = Post::create($request->all());
        return new  PostDetailResource($post->loadMissing(['Author:id,username']));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with(['Author:id,username'])->findOrFail($id);
        return new  PostDetailResource($post->loadMissing(['Author:id,username', 'Comments:id,post_id,user_id,comments_content']));
    }

    // public function show2(string $id)
    // {
    //     $post = Post::findOrFail($id);
    //     return new  PostDetailResource($post);
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());
        return new  PostDetailResource($post->loadMissing(['Author:id,username']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return new  PostDetailResource($post->loadMissing(['Author:id,username']));
    }
}
