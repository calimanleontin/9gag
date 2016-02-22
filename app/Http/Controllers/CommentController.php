<?php

namespace App\Http\Controllers;

use App\Comments;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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
}
