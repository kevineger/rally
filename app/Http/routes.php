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

Route::get('test', function () {
    return "test is a success";
});
Route::get('/', function () {
    return view('home');
});

Route::get('redditor', [
    'uses' => 'RedditorsController@index',
    'as'   => 'redditor.index'
]);
Route::get('redditor/{redditor}', [
    'uses' => 'RedditorsController@show',
    'as'   => 'redditor.show'
]);
Route::get('image', [
    'uses' => 'ImagesController@index',
    'as'   => 'image.index'
]);
Route::get('top-images/{subreddit}', [
    'uses' => 'ImagesController@analyzeSubreddit',
    'as'   => 'image.analyze'
]);
Route::group(['prefix' => 'big-data'], function () {
    Route::get('/', [
        'uses' => 'BigDataController@index',
        'as'   => 'data.index'
    ]);
    Route::get('updateChart', [
        'uses' => 'BigDataController@updateChart',
        'as'   => 'data.update'
    ]);
});

/*
|--------------------------------------------------------------------------
| Clustering
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'subreddit'], function () {
    Route::get('/', [
        'uses' => 'SubredditController@index',
        'as'   => 'cluster.index'
    ]);
    Route::get('get-data', [
        'uses' => 'SubredditController@clusterSubreddit',
        'as'   => 'cluster.getData'
    ]);
    Route::get('test', 'SubredditController@forceRecluster');
    Route::get('{subreddit}', [
        'uses' => 'SubredditController@show',
        'as'   => 'cluster.show'
    ]);
});

/*
|--------------------------------------------------------------------------
| Subreddit
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'subreddit'], function () {
    Route::get('/', [
        'as'   => 'subreddit.index',
        'uses' => 'SubredditController@index',
    ]);
    Route::get('show', [
        'as'   => 'subreddit.show',
        'uses' => 'SubredditController@show',
    ]);
});