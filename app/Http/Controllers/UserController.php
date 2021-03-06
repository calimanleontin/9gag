<?php

namespace App\Http\Controllers;

use App\Posts;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use \Auth;
use Illuminate\Mail;
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

    public function getChange()
    {
        $user = Auth::user();
        if($user == null)
            return redirect('/auth/login')->withErrors('You are not logged in');
        return view('auth.changePassword');
    }

    public function postChange(Request $request)
    {
        $user = Auth::user();
        if($user == null)
            return redirect('/auth/login')->withErrors('You are not logged in');
        $oldPassword = Input::get('oldPassword');
        $newPassword = Input::get('newPassword');
        $confirm = Input::get('confirm');
        if($user->password == '')
        {
            if($newPassword != $confirm)
            {
                return redirect('/auth/change-password')->withErrors('Passwords does not match');
            }
            else
            {
                $newPassword = bcrypt($newPassword);
                $user->password = $newPassword;
                $user->save();
                return redirect('/auth/profile')->withMessage('Password changed successfully');
            }
        }

            if(Hash::check($oldPassword, $user->password))
        {
            if($newPassword != $confirm)
            {
                return redirect('/auth/change-password')->withErrors('Passwords does not match');
            }
            else
            {
                $newPassword = bcrypt($newPassword);
                $user->password = $newPassword;
                $user->save();
                return redirect('/auth/profile')->withMessage('Password changed successfully');
            }
        }
        else
            return redirect('/auth/change-password')->withErrors('Wrong Password');
    }

    public function profile()
    {

        $user = Auth::user();
        if($user == null)
            return redirect('/auth/login')->withErrors('You are not logged in');
        $posts = $user->posts;
        $latest_posts = $posts->take(5);
        $latest_comments = $user->comments->take(5);
        $posts_count = count($posts);
        return view('auth.profile')
            ->withUser($user)
            ->with('posts_count', $posts_count)
            ->with('latest_posts', $latest_posts)
            ->with('latest_comments',$latest_comments);
    }

    public function allPosts()
    {
        $user = Auth::user();
        if($user == null)
            return redirect('/auth/login')->withErrors('You are not logged in');
        $posts = Posts::where('user_id', $user->id)->paginate(5);
        return view('home')->with('posts', $posts)->with('title', 'My posts');
    }

    public function showUser($id)
    {
        $user = User::find($id);
        if($user == null)
            return redirect('/')->withErrors('The user does not exist anymore');
        $posts = $user->posts;
        $latest_posts = $posts->take(5);
        $latest_comments = $user->comments->take(5);
        $posts_count = count($posts);
        return view('auth.profile')
            ->withUser($user)
            ->with('posts_count', $posts_count)
            ->with('latest_posts', $latest_posts)
            ->with('latest_comments',$latest_comments);
    }

    public function getManagement()
    {
        /**
         * @var $admin User
         */
        $admin = Auth::user();
        if($admin == null or $admin->is_admin() == false)
            return redirect('/')->withErrors('You have not sufficient permissions');
        $users = User::all();

        $roles = [ 'admin', 'user', 'moderator'];
        return view('auth.management')
            ->with('users', $users)
            ->with('roles', $roles);
    }

    public function postManagement(Request $request)
    {
        $admin = Auth::user();
        if($admin == null or $admin->is_admin() == false)
            return redirect('/')->withErrors('You have not sufficient permissions');
        $name = Input::get('user');
        $role = Input::get('role');
        if($name == 'Select a User')
            return redirect('/auth/management')->withErrors('You did not pick an user.');
        $user = User::where('name', $name)->first();
        if($user == null)
            return redirect('/')->withErrors('Internal Error');
        if($role == 'Select a role')
            return redirect('/auth/management')->withErrors('You did not pick a role.');
        if($role == null)
            return redirect('/')->withErrors('Internal Error');

        $user->role = $role;
        $user->save();
        return redirect('/')->withMessage('Done');

    }

    public function forgotPassword()
    {
        Mail::send('home',['name'=> 'leontin'],function($message){

            $message->to('calimanleontin@gmail.com', 'leontin')->from('calimanleontin@gmail.com')->subject('welcome');
        });
    }
}
