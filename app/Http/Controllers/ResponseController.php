<?php

namespace App\Http\Controllers;

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
}
