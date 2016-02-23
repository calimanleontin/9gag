<?php

namespace App\Http\Controllers;

use App\Comments;
use App\ResponseRating;
use App\Responses;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        if($user == null)
            return redirect('/')->withErrors('You are not logged in');
        $response = new Responses();
        $response->content = Input::get('content');
        $response->comment_id = $request->input('comment_id');
        $response->user_id = $user->id;
        $response->save();
        return redirect('/gag/'.Input::get('post_slug'))->withMessage('Replay saved.');
    }

    public function delete($response_id)
    {
        $user = Auth::user();
        if($user == null)
            return redirect('/')->withErrors('You are not logged in');
        $response = Responses::find($response_id);
        if($response == null)
            return redirect('/')->withErrors('The reply does not exist');
        if($user->id != $response->id and $user->is_admin() == false)
            return redirect ('/')->withErrors('You have not sufficient permissions');

        $comment = $response->comment;
        $post = $comment->post;

        $response->delete();
        return redirect('/gag/'.$post->slug)->withMessage('Comment deleted');
    }

    public function like($id)
    {
        $user = Auth::user();
        if($user == null)
            return redirect('/auth/login')->withErrors('You are not logged in');
        $response = Responses::find($id);
        if($response == null)
            return redirect('/')->withErrors('Comment does not exist');
        $comment = $response->comment;
        if($comment == null)
            return redirect('/')->withErrors('Internal error');
        $post = $comment->post;
        if($post == null)
            return redirect('/')->withErrors('Internal error');

        $rating = ResponseRating::where('response_id', $id)->where('user_id', $user->id)->first();


        if($rating == null)
        {
            $response->votes = 1;
            $response->save();
            $rating = new ResponseRating();
            $rating->likes = 1;
            $rating->user_id = $user->id;
            $rating->response_id = $comment->id;
            $rating->save();
            return redirect('/gag/'.$post->slug)->withMessage('Success');
        }
        if($rating->likes == 1)
        {
            $response->votes -= 1;
            $response->save();
            $rating->likes = 0;
            $rating->dislikes = 0;
            $rating->save();
            return redirect('/gag/'.$post->slug)->withMessage('Success');
        }
        if($rating->likes == 0)
        {
            if($rating->dislikes == 1)
            {
                $response->votes += 2;
                $response->save();
            }
            else
            {
                $response->votes += 1;
                $response->save();
            }
            $rating->likes = 1;
            $rating->dislikes= 0;
            $rating->save();
            return redirect('/gag/'.$post->slug)->withMessage('Success');
        }
    }

    public function dislike($id)
    {
        $user = Auth::user();
        if($user == null)
            return redirect('/auth/login')->withErrors('You are not logged in');
        $response = Responses::find($id);
        if($response == null)
            return redirect('/')->withErrors('Comment does not exist');
        $comment = $response->comment;
        if($comment == null)
            return redirect('/')->withErrors('Internal error');
        $post = $comment->post;
        if($post == null)
            return redirect('/')->withErrors('Internal error');

        $rating = ResponseRating::where('response_id', $id)->where('user_id', $user->id)->first();

        if($rating == null)
        {
            $response->votes = -1;
            $response->save();

            $rating = new ResponseRating();
            $rating->comment_id = $comment->id;
            $rating->user_id = $user->id;
            $rating->dislikes = 1;
            $rating->save();
            return redirect('/gag/'.$post->slug)->withMessage('Success');
        }

        if($rating->dislikes == 1)
        {
            $response->votes += 1;
            $response->save();

            $rating->dislikes = 0;
            $rating->likes = 0;
            $rating->save();
            return redirect('/gag/'.$post->slug)->withMessage('Success');
        }

        if($rating->dislikes == 0)
        {
            if($rating->likes == 1)
            {
                $response->votes -= 2;
                $response->save();
            }
            else
            {
                $response->votes -= 1;
                $response->save();
            }
            $rating->dislikes = 1;
            $rating->likes = 0;
            $rating->save();
            return redirect('/gag/'.$post->slug)->withMessage('Success');
        }
    }

}
