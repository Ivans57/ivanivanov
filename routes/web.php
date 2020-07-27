<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//this was a page by Laravel default

/*Route::get('/', function () {
    //return view('welcome');
    return view('pages.home');
});*/

//This is a route for test controller. We need this controller to make some 
//necessary experiments
Route::get('test', 'TestController@index');


//We use Route::group ... because our website has two languages
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('/', 'HomeController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('/', 'HomeController@index');
});


Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('about-me', 'AboutMeController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('about-me', 'AboutMeController@index');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('albums', 'AlbumsController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('albums', 'AlbumsController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('albums/{keyword}/page/{page}', 'AlbumsController@show');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('albums/{keyword}/page/{page}', 'AlbumsController@show');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('articles', 'ArticlesController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('articles', 'ArticlesController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('articles/{keyword}/page/{page}', 'ArticlesController@showFolder');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('articles/{keyword}/page/{page}', 'ArticlesController@showFolder');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('articles/{keyword}', 'ArticlesController@showArticle');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('articles/{keyword}', 'ArticlesController@showArticle');
});

/*Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('testik', 'AlbumsController@test');
});*/

//we need to add ['web'] group of middleware to get rid of extra protection for a specific route
/*Route::group(['middleware' => ['web']], function () {
    // Put all your routes inside here.
    Route::post('testik', 'AlbumsController@testik');
});*/

Route::group(['middleware' => ['web'], 'locale'], function() {
    Route::post('testik', 'AlbumsController@testik');
});

Route::group(['prefix' => 'ru', 'middleware' => ['web'], 'locale'], function() {
    Route::post('testik', 'AlbumsController@testik');
});

//This is for Admin Panel
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin', 'AdminController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin', 'AdminController@index');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/albums', 'AdminAlbumsController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/albums', 'AdminAlbumsController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/albums/{keyword}/page/{page}', 'AdminAlbumsController@show');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/albums/{keyword}/page/{page}', 'AdminAlbumsController@show');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin/findParents', 'AdminParentsController@findParents');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('admin/findParents', 'AdminParentsController@findParents');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin/getParentList', 'AdminParentsController@getParentList');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('admin/getParentList', 'AdminParentsController@getParentList');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/albums/create/{parent_keyword}', 'AdminAlbumsController@create');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/albums/create/{parent_keyword}', 'AdminAlbumsController@create');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('admin/albums', 'AdminAlbumsController@store');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin/albums', 'AdminAlbumsController@store');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/albums/{keyword}/edit/{parent_keyword}', 'AdminAlbumsController@edit');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/albums/{keyword}/edit/{parent_keyword}', 'AdminAlbumsController@edit');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::put('admin/albums/{keyword}', 'AdminAlbumsController@update');
});

Route::group(['middleware' => 'locale'], function() {
    Route::put('admin/albums/{keyword}', 'AdminAlbumsController@update');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/albums/{keyword}/delete', 'AdminAlbumsController@delete');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/albums/{keyword}/delete', 'AdminAlbumsController@delete');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::delete('admin/albums/{keyword}', 'AdminAlbumsController@destroy');
});

Route::group(['middleware' => 'locale'], function() {
    Route::delete('admin/albums/{keyword}', 'AdminAlbumsController@destroy');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/pictures/create/{parent_keyword}', 'AdminPicturesController@create');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/pictures/create/{parent_keyword}', 'AdminPicturesController@create');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('admin/pictures', 'AdminPicturesController@store');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin/pictures', 'AdminPicturesController@store');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/pictures/{keyword}/edit/{parent_keyword}', 'AdminPicturesController@edit');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/pictures/{keyword}/edit/{parent_keyword}', 'AdminPicturesController@edit');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::put('admin/pictures/{keyword}', 'AdminPicturesController@update');
});

Route::group(['middleware' => 'locale'], function() {
    Route::put('admin/pictures/{keyword}', 'AdminPicturesController@update');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/articles', 'AdminArticlesController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/articles', 'AdminArticlesController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/articles/{keyword}/page/{page}', 'AdminArticlesController@showFolder');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/articles/{keyword}/page/{page}', 'AdminArticlesController@showFolder');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/articles/create/{parent_keyword}', 'AdminArticlesController@create');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/articles/create/{parent_keyword}', 'AdminArticlesController@create');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('admin/articles', 'AdminArticlesController@store');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin/articles', 'AdminArticlesController@store');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/articles/{keyword}/edit/{parent_keyword}', 'AdminArticlesController@edit');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/articles/{keyword}/edit/{parent_keyword}', 'AdminArticlesController@edit');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::put('admin/articles/{keyword}', 'AdminArticlesController@update');
});

Route::group(['middleware' => 'locale'], function() {
    Route::put('admin/articles/{keyword}', 'AdminArticlesController@update');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/articles/{keyword}/delete', 'AdminArticlesController@delete');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/articles/{keyword}/delete', 'AdminArticlesController@delete');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::delete('admin/articles/{keyword}', 'AdminArticlesController@destroy');
});

Route::group(['middleware' => 'locale'], function() {
    Route::delete('admin/articles/{keyword}', 'AdminArticlesController@destroy');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/keywords', 'AdminKeywordsController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/keywords', 'AdminKeywordsController@index');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/keywords/create', 'AdminKeywordsController@create');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/keywords/create', 'AdminKeywordsController@create');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('admin/keywords', 'AdminKeywordsController@store');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin/keywords', 'AdminKeywordsController@store');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/keywords/{keyword}/edit', 'AdminKeywordsController@edit');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/keywords/{keyword}/edit', 'AdminKeywordsController@edit');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::put('admin/keywords/{keyword}', 'AdminKeywordsController@update');
});

Route::group(['middleware' => 'locale'], function() {
    Route::put('admin/keywords/{keyword}', 'AdminKeywordsController@update');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/keywords/{keyword}', 'AdminKeywordsController@remove');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/keywords/{keyword}', 'AdminKeywordsController@remove');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::delete('admin/keywords/{keyword}', 'AdminKeywordsController@destroy');
});

Route::group(['middleware' => 'locale'], function() {
    Route::delete('admin/keywords/{keyword}', 'AdminKeywordsController@destroy');
});