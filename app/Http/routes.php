<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/','PostController@index');
    Route::get('/trending', 'PostController@trending');
    Route::get('/fresh', 'PostController@fresh');
    Route::get('/gag/post/like/{id}','PostController@like');
    Route::get('/gag/post/dislike/{id}','PostController@dislike');
    Route::get('/gag/{slug}', 'PostController@show');

    Route::post('/gag/comment/store','CommentController@store');
    Route::get('/gag/comment/like/{post_id}/{comment_id}','CommentController@like');
    Route::get('/gag/comment/dislike/{post_id}/{comment_id}','CommentController@dislike');
    Route::get('/gag/comment/delete/{id}', 'CommentController@delete');

    Route::post('/gag/response/store', 'ResponseController@store');
    Route::get('/gag/response/like/{id}','ResponseController@like');
    Route::get('/gag/response/dislike/{id}','ResponseController@dislike');
    Route::get('gag/response/delete/{id}', 'ResponseController@delete');


    Route::get('/auth/login','UserController@getLogin');
    Route::post('/auth/login','UserController@postLogin');
    Route::get('/auth/register','UserController@getRegister');
    Route::post('/auth/register','UserController@postRegister');
    Route::get('/auth/logout','UserController@logout');

    Route::get('/post/create','PostController@create');
    Route::post('/post/store','PostController@store');
    Route::get('/post/manage','PostController@getManage');
    Route::get('post/decline/{id}','PostController@decline');
    Route::get('/post/accept/{id}','PostController@accept');



    Route::get('/make-categories','PostController@makeCategories');

    Route::get('auth/facebook', 'Auth\AuthController@redirectToProvider');
    Route::get('auth/facebook/callback', 'Auth\AuthController@handleProviderCallback');

});
