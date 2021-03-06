<?php

namespace App\Http\Controllers;

use App\Comments;
use App\PostRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Posts;
use App\User;
use App\Categories;
use Illuminate\Support\Str;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function index()
    {
        if(\Cache::has('hot'))
            $posts = \Cache::get('hot');
        else
        {
            $posts = Posts::where('category_id',1)->where('accepted',true)->paginate(5);
            \Cache::add('hot', $posts, 10);
        }
        return view('home')->with('posts',$posts)->with('title','Hot page');
    }

    public function trending()
    {
        if(\Cache::has('trending'))
            $posts = \Cache::get('trending');
        else {
            $posts = Posts::where('category_id', 2)->where('accepted', true)->paginate(5);
            \Cache::add('trending', $posts, 10);
        }
        return view('home')->with('posts',$posts)->with('title','Trending page');
    }

    public function fresh()
    {
        if(\Cache::has('fresh'))
            $posts = \Cache::get('fresh');
        else {
            $posts = Posts::where('category_id', 3)->where('accepted', true)->paginate(5);
            \Cache::add('fresh', $posts, 10);
        }
        return view('home')->with('posts',$posts)->with('title','Fresh page');
    }

    public function create()
    {
        return view('post.create');
    }

    public function show($slug)
    {
        $rating = null;
        $post = Posts::where('slug', $slug)->first();
        if($post == null)
            return redirect('/')->withErrors('The post does not exist.');
        if(\Auth::user() != null)
            $rating = PostRating::where('user_id',\Auth::user()->id)->where('post_id', $post->id)->first();
        $post->update(array('views' => 1));
        $upVotes = $post->votes > 0 ? $post->votes : 0;
        if($post == null)
            return redirect('/')->withErrors('The post does not exist');
        $comments = $post->comments;
        if($rating == null)
            return view('post.show')
                ->withPost($post)
                ->withVotes($upVotes)
                ->withComments($comments);
        else
        {
            return view('post.show')
                ->withPost($post)
                ->withRating($rating)
                ->withVotes($upVotes)
                ->withComments($comments);
        }
    }

    public function store(Request $request)
    {
        $comment = Categories::find(1);
        if($comment == null)
            return redirect('/make-categories')->withErrors('Dude you forget the categories. Again.');
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
        return redirect('/post/manage')
            ->withMessage('deleted');
    }

    public function accept(Request $request, $id)
    {
        $post = Posts::where('id',$id)->first();
        $post->accepted = true;
        $post->category_id = 3;
        $post->save();
        return redirect('/post/manage')
            ->withMessage('accepted');
    }

    public function like($id)
    {
        if(\Auth::user() == null)
            return redirect('/auth/login')->withErrors('You have to log in to like');
        $post = Posts::find($id);
        if($post == null)
            return redirect('/')->withErrors('Post does not exist');
        $user_id = \Auth::user()->id;
        $rating = PostRating::where('user_id',$user_id)->where('post_id',$id)->first();
        if($rating == null)
        {
            $post->votes = 1;
            $post->save();
            $rating = new PostRating();
            $rating->likes = 1;
            $rating->user_id = $user_id;
            $rating->post_id = $post->id;
            $rating->save();
        }
        else
        if($rating->likes == 1)
        {
            $post->votes -= 1;
            $post->save();
            $rating->likes = 0;
            $rating->dislikes = 0;
            $rating->save();
        }
        else
        if($rating->likes == 0)
        {
            if($rating->dislikes == 1)
            {
                $post->votes += 2;
                $post->save();

            }
            else
            {
                $post->votes += 1;
                $post->save();
            }
            $rating->likes = 1;
            $rating->dislikes = 0;
            $rating->save();
        }
        if($post->votes < 5)
            $post->category_id = 3;


        if($post->votes >= 5)
            $post->category_id = 2;

        if($post->votes >= 10)
            $post->category_id = 1;
        $post->save();
        return redirect('/gag/'.$post->slug);

    }

    public function dislike($id)
    {
        if(\Auth::user() == null)
            return redirect('/auth/login')->withErrors('You are not logged in');
        $post = Posts::find($id);
        if($post == null)
            return redirect('/')->withErrors('Post does not exist');
        $user_id = \Auth::user()->id;
        $rating = PostRating::where('user_id',$user_id)->where('post_id',$id)->first();
        if($rating == null)
        {
            $post->votes = -1;
            $post->save();
            $rating = new PostRating();
            $rating->user_id = $user_id;
            $rating->post_id = $post->id;
            $rating->dislikes = 1;
            $rating->save();
        }
        else
        if($rating->dislikes == 1)
        {
            $post->votes += 1;
            $post->save();
            $rating->likes = 0;
            $rating->dislikes = 0;
            $rating->save();
        }
        else
        if($rating->dislikes == 0)
        {
            if($rating->likes == 1)
            {
                $post->votes -= 2;
                $post->save();
            }
            else
            {
                $post->votes -= 1;
                $post->save();

            }
            $rating->likes = 0;
            $rating->dislikes = 1;
            $rating->save();
        }

        if($post->votes < 5)
            $post->category_id = 3;

        if($post->votes >= 5)
            $post->category_id = 2;

        if($post->votes >= 10)
            $post->category_id = 1;
        $post->save();

        return redirect('/gag/'.$post->slug);

    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        $posts = Posts::where('title','like','%'.$q.'%')->paginate(5);
        return view('home')
            ->withPosts($posts)
            ->withTitle('Search result');
    }

    public function makeCategories()
    {
        $category = new Categories();
        $category->name = 'Hot';
        $category->save();

        $category = new Categories();
        $category->name = 'Trending';
        $category->save();

        $category = new Categories();
        $category->name = 'Fresh';
        $category->save();

        return redirect('/')->withMessage('Done!');
    }

    public function getPosts()
    {
        $term = Str::lower(Input::get('term'));
        $posts = Posts::all();
        $data = array();
        foreach($posts as $post)
            $data[$post->title] = $post->title;
        $return_array = array();

        foreach ($data as $k => $v) {
            if (strpos(Str::lower($v), $term) !== FALSE) {
                $return_array[] = array('value' => $v, 'id' =>$k);
            }
        }
        return \Response::json($return_array);
    }



}
