<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostDetailResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        $image = null;
        if ($request->file) {
            //Di sini memproses pemasukkan gambar ke database
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $image = $fileName . '.' . $extension;
            Storage::putFileAs('image', $request->file, $image);
        }
        $request['image'] = $image;
        $request['author_id'] = Auth::user()->id;
        $post = Post::create($request->all());
        return new PostDetailResource($post->loadMissing(['Author:id,username']));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with(['Author:id,username'])->findOrFail($id);
        return new PostDetailResource($post->loadMissing(['Author:id,username', 'Comments:id,post_id,user_id,comments_content']));
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

        // Periksa apakah ada file gambar baru yang dikirimkan
        if ($request->file) {
            // Hapus gambar lama jika ada
            if ($post->image) {
                Storage::delete('image/' . $post->image);
            }

            // Memproses pemasukkan gambar ke database
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $newImage = $fileName . '.' . $extension;
            Storage::putFileAs('image', $request->file, $newImage);

            // Update field image pada post
            $post->update(['image' => $newImage]);
        }

        // Update field lainnya
        $post->update([
            'title' => $request->title,
            'news_content' => $request->news_content,
            // tambahkan kolom lain yang ingin Anda update
        ]);

        return new PostDetailResource($post->loadMissing(['Author:id,username']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return new PostDetailResource($post->loadMissing(['Author:id,username']));
    }

    public function generateRandomString($length = 30)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
