<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Posts;
use App\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function index()
    {
        $posts = Posts::where('category_id',1)->where('accepted',true)->paginate(5);
        return view('home')->with('posts',$posts)->with('title','Hot page');
    }

    public function trending()
    {
        $posts = Posts::where('category_id',2)->where('accepted',true)->paginate(5);
        return view('home')->with('posts',$posts)->with('title','Trending page');
    }

    public function fresh()
    {
        $posts = Posts::where('category_id',3)->where('accepted',true)->paginate(5);
        return view('home')->with('posts',$posts)->with('title','Fresh page');
    }

    public function create()
    {
        return view('post.create');
    }

    public function show($slug)
    {
        $post = Posts::where('slug', $slug)->first();
        $post->update(array('views' => 1));
        if($post == null)
            return redirect('/')->withErrors('The post does not exist');
        return view('post.show')->withPost($post);
    }

    public function store(Request $request)
    {
        $title = $request->input('title');
        $file = Input::file('image');
        $user = $request->user();
        if($title == null)
            return redirect('/post/create')->withErrors('Title can\'t be empty');
        if($file == null)
            return redirect('/post/create')->withErrors('Image can\'t be empty');
        $post = new Posts();
        $post->title=$title;
        $post->slug = str_slug($request->input('title'));
        $destinationPath = 'images/catalog';
        $name = date('Y-m-d-h-M-s-ms').$file->getClientOriginalName();
        $post->image = $name;
        $post->category_id = 3;
        $file->move($destinationPath,$name);
        $post->user_id = $user->id;
        $post->views = 0;
        $post->save();
        return redirect('/')->withMessage('Photo saved successfully but first must be accepted');
    }

    public function getManage(Request $request)
    {
        if($request->user() != null and $request->user()->is_admin()) {

            $posts = Posts::where('accepted', false)->paginate(5);
            return view('post.manage')->withPosts($posts)->withTitle('Manage posts');
        }
        else
            return redirect('/')->withErrors('You have not sufficient permissions');
    }

    public function decline(Request $request,$id)
    {
        $post = Posts::where('id',$id)->first();
        $post->delete();
        return redirect('/');
    }

    public function accept(Request $request, $id)
    {
        $post = Posts::where('id',$id)->first();
        $post->accepted = true;
        $post->category_id = 3;
        $post->save();
        return redirect('/');
    }

    public function like($id)
    {

    }

    public function dislike($id)
    {

    }
}
