<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('updated_at', 'desc')->get();
        return view('post.index', compact('posts'));
    }

    public function create()//home page
    {
        return view('post.create');
    }

    public function store(Request $request)//saving
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post = new Post();//select model(database)
        $post->title = $validatedData['title'];
        $post->body = $validatedData['body'];
        $post->user_id = Auth::id();
        $post->save();//save to database

        return redirect()->route('post.index')->with('success', '投稿が作成されました');
    }

    public function myPosts()//read my post
    {
        $posts = Post::where('user_id', Auth::id())->orderBy('updated_at', 'desc')->get();// where: condition
        return view('my-posts', compact('posts'));
    }

    public function edit($id)//edit my post
    {
        $post = Post::findOrFail($id);
        return view('post.edit', compact('post'));
    }

    public function update(Request $request, $id)//update 
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',//255: max litereture
            'body' => 'required|string',
        ]);

        $post = Post::findOrFail($id);
        $post->title = $validatedData['title'];
        $post->body = $validatedData['body'];
        $post->save();

        return redirect()->route('myposts')->with('success', '投稿が更新されました');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('myposts')->with('success', '投稿が削除されました');
    }
}

