<?php

namespace App\Http\Controllers;

use App\CommentRating;
use App\Comments;
use App\Posts;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        if($request->user() == null)
            return redirect('/')->withErrors('You are not logged in');
        $slug = $request->input('slug');
        $post_id = $request->input('post_id');
        $content = $request->input('content');
        if($content == '')
            return redirect('/gag/'.$slug)->withErrors('Message can\'t be empty');
        $comment = new Comments();
        $comment->post_id = $post_id;
        $user_id = $request->user()->id;
        $comment->user_id = $user_id;
        $comment->content = $content;
        $comment->save();
        return redirect('/gag/'.$slug)->withMessage('Comment added successfully');
    }

    public function like($post_id, $comment_id)
    {
        $user = Auth::user();
        if($user == null)
            return redirect('/')->withErrors('You are not logged in');
        $comment = Comments::find($comment_id);
        $post = Posts::find($post_id);
        if($post == null)
            return redirect('/')->withErrors('Post does not exist');
        if($comment == null)
            return redirect ('/gag/'.$post->slug)->withErrors('Comment does not exist');
        $rating = CommentRating::where('comment_id', $comment->id)->where('user_id', $user->id)->first();

        if($rating == null)
        {
            $comment->votes = 1;
            $comment->save();
            $rating = new CommentRating();
            $rating->likes = 1;
            $rating->user_id = $user->id;
            $rating->comment_id = $comment->id;
            $rating->save();
            return redirect('/gag/'.$post->slug)->withMessage('Success');
        }
        if($rating->likes == 1)
        {
            $comment->votes -= 1;
            $comment->save();
            $rating->likes = 0;
            $rating->dislikes = 0;
            $rating->save();
            return redirect('/gag/'.$post->slug)->withMessage('Success');
        }
        if($rating->likes == 0)
        {
            if($rating->dislikes == 1)
            {
                $comment->votes += 2;
                $comment->save();
            }
            else
            {
                $comment->votes += 1;
                $comment->save();
            }
            $rating->likes = 1;
            $rating->dislikes= 0;
            $rating->save();
            return redirect('/gag/'.$post->slug)->withMessage('Success');
        }

    }

    public function dislike($post_id, $comment_id)
    {
        $user = Auth::user();
        if($user == null)
            return redirect('/')->withErrors('You are not logged in');
        $comment = Comments::find($comment_id);
        $post = Posts::find($post_id);
        if($post == null)
            return redirect('/')->withErrors('Post does not exist');
        if($comment == null)
            return redirect ('/gag/'.$post->slug)->withErrors('Comment does not exist');
        $rating = CommentRating::where('comment_id', $comment->id)->where('user_id', $user->id)->first();

        if($rating == null)
        {
            $comment->votes = -1;
            $comment->save();

            $rating = new CommentRating();
            $rating->comment_id = $comment->id;
            $rating->user_id = $user->id;
            $rating->dislikes = 1;
            $rating->save();
            return redirect('/gag/'.$post->slug)->withMessage('Success');
        }

        if($rating->dislikes == 1)
        {
            $comment->votes += 1;
            $comment->save();

            $rating->dislikes = 0;
            $rating->likes = 0;
            $rating->save();
            return redirect('/gag/'.$post->slug)->withMessage('Success');
        }

        if($rating->dislikes == 0)
        {
            if($rating->likes == 1)
            {
                $comment->votes -= 2;
                $comment->save();
            }
            else
            {
                $comment->votes -= 1;
                $comment->save();
            }
            $rating->dislikes = 1;
            $rating->likes = 0;
            $rating->save();
            return redirect('/gag/'.$post->slug)->withMessage('Success');
        }
    }

    public function delete($id)
    {
        $user = Auth::user();
        if($user == null)
            return redirect('/auth/login')->withErrors('You are not logged in');
        $comment = Comments::find($id);
        if($comment == null)
            return redirect('/')->withErrors('The comment does not exist');
        $post = $comment->post;
        if($post == null)
            return redirect('/')->withErrors('Internal error');
        if($comment->id == $user->id or $user->is_admin())
        {
            $comment->delete();
            return redirect('/gag/'.$post->slug)->withMessage('Deleted');
        }
        else
            return redirect('/gag/'.$post->slug)->withErrors('You have not sufficient permissions');
    }
}
