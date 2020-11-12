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

//******************************************************************************
//Website
//******************************************************************************

//Home

//We use Route::group ... because our website has two languages

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('/', 'HomeController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('/', 'HomeController@index');
});

//------------------------------------------------------------------------------

//About me

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('about-me', 'AboutMeController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('about-me', 'AboutMeController@index');
});

//------------------------------------------------------------------------------

//Albums

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('albums/{sorting_mode?}', 'AlbumsController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('albums/{sorting_mode?}', 'AlbumsController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('albums/{keyword}/page/{page}/{sorting_mode?}/{albums_or_pictures_first?}', 'AlbumsController@show');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('albums/{keyword}/page/{page}/{sorting_mode?}/{albums_or_pictures_first?}', 'AlbumsController@show');
});

//------------------------------------------------------------------------------

//Articles

//sorting_mode argument is optional. It is required only for sorting.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('articles/{sorting_mode?}', 'ArticlesController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('articles/{sorting_mode?}', 'ArticlesController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('articles/{keyword}/page/{page}/{sorting_mode?}/{folders_or_articles_first?}', 'ArticlesController@showFolder');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('articles/{keyword}/page/{page}/{sorting_mode?}/{folders_or_articles_first?}', 'ArticlesController@showFolder');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('articles/{keyword}', 'ArticlesController@showArticle');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('articles/{keyword}', 'ArticlesController@showArticle');
});

//------------------------------------------------------------------------------

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

//==============================================================================

//******************************************************************************
//Admin Panel
//******************************************************************************

//Start

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin', 'AdminController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin', 'AdminController@index');
});

//------------------------------------------------------------------------------

//Home

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/home', 'AdminHomeController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/home', 'AdminHomeController@index');
});

//------------------------------------------------------------------------------

//About me

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/about-me', 'AdminAboutMeController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/about-me', 'AdminAboutMeController@index');
});

//------------------------------------------------------------------------------

//Albums and Pictures

//Albums

//sorting_mode argument is optional. It is required only for sorting.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/albums/{sorting_mode?}', 'AdminAlbumsController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/albums/{sorting_mode?}', 'AdminAlbumsController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/albums/{keyword}/page/{page}/{sorting_mode?}', 'AdminAlbumsController@show');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/albums/{keyword}/page/{page}/{sorting_mode?}', 'AdminAlbumsController@show');
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

//Delete will be the same for albums and pictures.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/albums/delete/{entity_types_and_keywords}', 'AdminAlbumsController@delete');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/albums/delete/{entity_types_and_keywords}', 'AdminAlbumsController@delete');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::delete('admin/albums/{entity_types_and_keywords}', 'AdminAlbumsController@destroy');
});

Route::group(['middleware' => 'locale'], function() {
    Route::delete('admin/albums/{entity_types_and_keywords}', 'AdminAlbumsController@destroy');
});
//End of Albums

//Pictures
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

//End of Pictures

//------------------------------------------------------------------------------

//Articles and Folders

//Folders


//sorting_mode argument is optional. It is required only for sorting.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/articles/{sorting_mode?}', 'AdminArticlesController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/articles/{sorting_mode?}', 'AdminArticlesController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/articles/{keyword}/page/{page}/{show_invisible}/{sorting_mode?}/{folders_or_articles_first?}', 'AdminArticlesController@showFolder');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/articles/{keyword}/page/{page}/{show_invisible}/{sorting_mode?}/{folders_or_articles_first?}', 'AdminArticlesController@showFolder');
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

//Delete will be the same for folders and articles.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/articles/delete/{entity_types_and_keywords}', 'AdminArticlesController@delete');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/articles/delete/{entity_types_and_keywords}', 'AdminArticlesController@delete');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::delete('admin/articles/{entity_types_and_keywords}', 'AdminArticlesController@destroy');
});

Route::group(['middleware' => 'locale'], function() {
    Route::delete('admin/articles/{entity_types_and_keywords}', 'AdminArticlesController@destroy');
});
//End of Folders

//Articles
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/article/create/{parent_keyword}', 'AdminArticleController@create');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/article/create/{parent_keyword}', 'AdminArticleController@create');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('admin/article', 'AdminArticleController@store');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin/article', 'AdminArticleController@store');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/article/{parent_keyword}/edit/{keyword}', 'AdminArticleController@edit');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/article/{parent_keyword}/edit/{keyword}', 'AdminArticleController@edit');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::put('admin/article/{keyword}', 'AdminArticleController@update');
});

Route::group(['middleware' => 'locale'], function() {
    Route::put('admin/article/{keyword}', 'AdminArticleController@update');
});
//End of Articles

//------------------------------------------------------------------------------

//Keywords
//This route should be written before index, otherwise "create" will be considered as variable.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/keywords/create', 'AdminKeywordsController@create');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/keywords/create', 'AdminKeywordsController@create');
});

//sorting_mode argument is optional. It is required only for sorting.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/keywords/{sorting_mode?}', 'AdminKeywordsController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/keywords/{sorting_mode?}', 'AdminKeywordsController@index');
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
    Route::get('admin/keywords/delete/{keywords}', 'AdminKeywordsController@remove');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/keywords/delete/{keywords}', 'AdminKeywordsController@remove');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::delete('admin/keywords/{keywords}', 'AdminKeywordsController@destroy');
});

Route::group(['middleware' => 'locale'], function() {
    Route::delete('admin/keywords/{keywords}', 'AdminKeywordsController@destroy');
});

//------------------------------------------------------------------------------

//Parent Search

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

//------------------------------------------------------------------------------

//==============================================================================