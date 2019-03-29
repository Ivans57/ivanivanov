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
    Route::get('/', 'AboutMeController@index');
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
    Route::get('albums/{keyword}/page/{page}', 'AlbumsController@showAlbum');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('albums/{keyword}/page/{page}', 'AlbumsController@showAlbum');
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

/*Route::group(['middleware' => ['web'], 'locale'], function() {
    Route::post('albums/refresh', 'AlbumsController@refreshAlbum');
});

Route::group(['prefix' => 'ru', 'middleware' => ['web'], 'locale'], function() {
    Route::post('albums/refresh', 'AlbumsController@refreshAlbum');
});*/



//This is for Admin Panel
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin', 'AdminController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin', 'AdminController@index');
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
    Route::get('admin/articles/new-article', 'AdminArticlesController@create');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/articles/new-article', 'AdminArticlesController@create');
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

/*Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin', 'AdminController@editHome');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin', 'AdminController@editHome');
});*/
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
