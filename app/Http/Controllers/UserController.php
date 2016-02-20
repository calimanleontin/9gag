<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use \Auth;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;


class UserController extends Controller
{
    public function getLogin(Request $request)
    {
        if($request->user() != null)
            return redirect('/')->withErrors('You are already logged in');
        return view('auth.login');
    }
    public function postLogin(Request $request)
    {
        if($request->user() != null)
            return redirect('/')->withErrors('You are already logged in');
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email',$email)->first();
        if($user == null)
            return redirect('/')->withErrors('Your email has not been found');
        if(Hash::check($password, $user->password))
        {
            Auth::login($user);
            return redirect('/')->withMessage('You have successfully logged in');
        }
        else
            return redirect('/auth/login')->withErrors('Wrong password');
    }

    public function getRegister(Request $request)
    {
        if($request->user() != null)
            return redirect('/')->withErrors('You are already logged in');
        return view('auth.register');
    }

    public function postRegister(Request $request)
    {
        if($request->user() != null)
            return redirect('/')->withErrors('You are already logged in');
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');
        $confirm = $request->input('confirm');
        if($password != $confirm)
            return view('auth.register')
                ->withErrors('The passwords doesn\'t match')
                ->with('username',$username)
                ->with('email',$email);
        /**
         * @var $copy \App\User
         */
        $copy = User::where('name',$username)->first();
        if($copy!= null)
            return redirect('/')->withErrors('Username already in use');
        $copy = User::where('email',$email)->first();
        if($copy!= null)
            return redirect('/')->withErrors('Email already registered');
        if($username == '' || $email == '' || $password == '')
            return view('auth.register')->withName($username)->withEmail($email)->withErrors('Please fill all the fields');
        $user = new User();
        $user->name = $username;
        $user->email = $email;
        $hash = bcrypt($password);
        $user->password = $hash;
        $user->save();
        Auth::login($user);
        return redirect('/')->withMessage('You have been registered successfully');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->withMessage('Logout successfully');
    }
}
