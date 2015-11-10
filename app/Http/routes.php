<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function ()
{
    return view('welcome');
});

Route::get('redditor', [
    'uses' => 'RedditorsController@index',
    'as'   => 'redditor.index'
]);
Route::get('redditor/{redditor}', [
    'uses' => 'RedditorsController@show',
    'as'   => 'redditor.show'
]);

Route::get('frontpage', [
    'as'   => 'frontpage',
    'uses' => 'PagesController@redditHomePage',
]);
