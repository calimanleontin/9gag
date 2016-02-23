<?php

namespace App\Http\Controllers;

use App\Comments;
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
        if($user->id != $response_id or (!$user->is_admin()))
            return redirect ('/')->withErrors('You have not sufficient permissions');
        $response = Responses::find($response_id);
        if($response == null)
            return redirect('/')->withErrors('The reply does not exist');

        $comment = $response->comment;
        $post = $comment->post;

        $response->delete();
        return redirect('/gag/'.$post->slug)->withMessage('Comment deleted');
    }
}
