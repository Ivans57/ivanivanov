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

Route::group(['middleware' => 'locale'], function() {
    Route::get('albums/{keyword}/page/{page}/{sorting_mode?}/{albums_or_pictures_first?}', 'AlbumsController@show');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('albums/{keyword}/page/{page}/{sorting_mode?}/{albums_or_pictures_first?}', 'AlbumsController@show');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('albums/search', 'AlbumsController@searchAlbumOrPicture');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('albums/search', 'AlbumsController@searchAlbumOrPicture');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('albums/{sorting_mode?}', 'AlbumsController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('albums/{sorting_mode?}', 'AlbumsController@index');
});

//------------------------------------------------------------------------------

//Articles

Route::group(['middleware' => 'locale'], function() {
    Route::get('articles/{keyword}/page/{page}/{sorting_mode?}/{folders_or_articles_first?}', 'ArticlesController@showFolder');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('articles/{keyword}/page/{page}/{sorting_mode?}/{folders_or_articles_first?}', 'ArticlesController@showFolder');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('articles/article/{keyword}', 'ArticlesController@showArticle');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('articles/article/{keyword}', 'ArticlesController@showArticle');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('articles/search', 'ArticlesController@searchFolderOrArticle');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('articles/search', 'ArticlesController@searchFolderOrArticle');
});

//sorting_mode argument is optional. It is required only for sorting.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('articles/{sorting_mode?}', 'ArticlesController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('articles/{sorting_mode?}', 'ArticlesController@index');
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

//Login

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin', 'Auth\LoginController@create');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin', 'Auth\LoginController@create');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('admin', 'Auth\LoginController@store');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin', 'Auth\LoginController@store');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/logout', 'Auth\LoginController@destroy');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/logout', 'Auth\LoginController@destroy');
});

//------------------------------------------------------------------------------

//Start

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/start', 'AdminController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/start', 'AdminController@index');
});

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
    Route::post('admin/keywords/search', 'AdminKeywordsController@searchKeyword');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin/keywords/search', 'AdminKeywordsController@searchKeyword');
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

//Users

//This route should be written before index, otherwise "create" will be considered as variable.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/users/create', 'AdminUsersController@create');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/users/create', 'AdminUsersController@create');
});

//sorting_mode argument is optional. It is required only for sorting.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/users/{sorting_mode?}', 'AdminUsersController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/users/{sorting_mode?}', 'AdminUsersController@index');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('admin/users', 'AdminUsersController@store');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin/users', 'AdminUsersController@store');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/users/{name}/edit', 'AdminUsersController@edit');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/users/{name}/edit', 'AdminUsersController@edit');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::put('admin/users/{name}', 'AdminUsersController@update');
});

Route::group(['middleware' => 'locale'], function() {
    Route::put('admin/users/{name}', 'AdminUsersController@update');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/users/delete/{names}', 'AdminUsersController@remove');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/users/delete/{names}', 'AdminUsersController@remove');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::delete('admin/users/{names}', 'AdminUsersController@destroy');
});

Route::group(['middleware' => 'locale'], function() {
    Route::delete('admin/users/{names}', 'AdminUsersController@destroy');
});

//------------------------------------------------------------------------------

//Users Add, Edit, Delete within sections. 

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/users-add-edit-delete/add-for-section/{section}', 'AdminUsersAddEditDeleteController@add_for_section');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/users-add-edit-delete/add-for-section/{section}', 'AdminUsersAddEditDeleteController@add_for_section');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('admin/users-add-edit-delete-for-section', 'AdminUsersAddEditDeleteController@join_for_section');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin/users-add-edit-delete-for-section', 'AdminUsersAddEditDeleteController@join_for_section');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/users-add-edit-delete/edit-for-section/{section}', 'AdminUsersAddEditDeleteController@edit_for_section');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/users-add-edit-delete/edit-for-section/{section}', 'AdminUsersAddEditDeleteController@edit_for_section');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::put('admin/users-add-edit-delete-for-section', 'AdminUsersAddEditDeleteController@update_for_section');
});

Route::group(['middleware' => 'locale'], function() {
    Route::put('admin/users-add-edit-delete-for-section', 'AdminUsersAddEditDeleteController@update_for_section');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/users-add-edit-delete/delete-for-section/{section}', 'AdminUsersAddEditDeleteController@delete_for_section');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/users-add-edit-delete/delete-for-section/{section}', 'AdminUsersAddEditDeleteController@delete_for_section');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::delete('admin/users-add-edit-delete-for-section', 'AdminUsersAddEditDeleteController@destroy_for_section');
});

Route::group(['middleware' => 'locale'], function() {
    Route::delete('admin/users-add-edit-delete-for-section', 'AdminUsersAddEditDeleteController@destroy_for_section');
});

//------------------------------------------------------------------------------

//Users Add, Edit, Delete within directories. 

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/users-add-edit-delete/add-for-directory/{section}/{directory}', 
                                                                            'AdminUserAddEditDeleteForDirectoryController@add_for_directory');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/users-add-edit-delete/add-for-directory/{section}/{directory}', 
                                                                            'AdminUserAddEditDeleteForDirectoryController@add_for_directory');
    
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('admin/users-add-edit-delete-for-directory', 'AdminUserAddEditDeleteForDirectoryController@join_for_directory');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin/users-add-edit-delete-for-directory', 'AdminUserAddEditDeleteForDirectoryController@join_for_directory');
});
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

//Delete will be the same for albums and pictures.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/albums/delete/{entity_types_and_keywords}/{parent_album}/{search_is_on?}', 'AdminAlbumsController@delete');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/albums/delete/{entity_types_and_keywords}/{parent_album}/{search_is_on?}', 'AdminAlbumsController@delete');
});
//Section needs to be specified, otherwise the system will be confused with the route.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::delete('admin/albums/{section}/{entity_types_and_keywords}/{parent_album}/{search_is_on?}', 'AdminAlbumsController@destroy');
});
//Section needs to be specified, otherwise the system will be confused with the route.
Route::group(['middleware' => 'locale'], function() {
    Route::delete('admin/albums/{section}/{entity_types_and_keywords}/{parent_album}/{search_is_on?}', 'AdminAlbumsController@destroy');
});

//sorting_mode argument is optional. It is required only for sorting.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/albums/{show_invisible?}/{sorting_mode?}', 'AdminAlbumsController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/albums/{show_invisible?}/{sorting_mode?}', 'AdminAlbumsController@index');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('admin/albums/search', 'AdminAlbumsController@searchAlbumOrPicture');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin/albums/search', 'AdminAlbumsController@searchAlbumOrPicture');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/albums/{keyword}/page/{page}/{show_invisible?}/{sorting_mode?}/{albums_or_pictures_first?}', 
               'AdminAlbumsController@show');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/albums/{keyword}/page/{page}/{show_invisible?}/{sorting_mode?}/{albums_or_pictures_first?}', 
               'AdminAlbumsController@show');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/albums/{keyword}/edit/{parent_keyword}/{search_is_on?}', 'AdminAlbumsController@edit');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/albums/{keyword}/edit/{parent_keyword}/{search_is_on?}', 'AdminAlbumsController@edit');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::put('admin/albums/{keyword}/{parent_album}/{search_is_on?}', 'AdminAlbumsController@update');
});

Route::group(['middleware' => 'locale'], function() {
    Route::put('admin/albums/{keyword}/{parent_album}/{search_is_on?}', 'AdminAlbumsController@update');
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
    Route::get('admin/pictures/{keyword}/edit/{parent_keyword}/{search_is_on?}', 'AdminPicturesController@edit');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/pictures/{keyword}/edit/{parent_keyword}/{search_is_on?}', 'AdminPicturesController@edit');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::put('admin/pictures/{keyword}/{parent_keyword}/{search_is_on?}', 'AdminPicturesController@update');
});

Route::group(['middleware' => 'locale'], function() {
    Route::put('admin/pictures/{keyword}/{parent_keyword}/{search_is_on?}', 'AdminPicturesController@update');
});

//End of Pictures

//------------------------------------------------------------------------------

//Articles and Folders

//Folders

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

//Delete will be the same for folders and articles.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/articles/delete/{entity_types_and_keywords}/{parent_folder}/{search_is_on?}', 'AdminArticlesController@delete');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/articles/delete/{entity_types_and_keywords}/{parent_folder}/{search_is_on?}', 'AdminArticlesController@delete');
});
//Section needs to be specified, otherwise the system will be confused with the route.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::delete('admin/articles/{section}/{entity_types_and_keywords}/{parent_folder}/{search_is_on?}', 'AdminArticlesController@destroy');
});
//Section needs to be specified, otherwise the system will be confused with the route.
Route::group(['middleware' => 'locale'], function() {
    Route::delete('admin/articles/{section}/{entity_types_and_keywords}/{parent_folder}/{search_is_on?}', 'AdminArticlesController@destroy');
});

//sorting_mode argument is optional. It is required only for sorting.
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/articles/{show_invisible?}/{sorting_mode?}', 'AdminArticlesController@index');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/articles/{show_invisible_arg?}/{sorting_mode?}', 'AdminArticlesController@index');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('admin/articles/search', 'AdminArticlesController@searchFolderOrArticle');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin/articles/search', 'AdminArticlesController@searchFolderOrArticle');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/articles/{keyword}/page/{page}/{show_invisible?}/{sorting_mode?}/{folders_or_articles_first?}', 
               'AdminArticlesController@showFolder');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/articles/{keyword}/page/{page}/{show_invisible?}/{sorting_mode?}/{folders_or_articles_first?}', 
               'AdminArticlesController@showFolder');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/articles/{keyword}/edit/{parent_keyword}/{search_is_on?}', 'AdminArticlesController@edit');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/articles/{keyword}/edit/{parent_keyword}/{search_is_on?}', 'AdminArticlesController@edit');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::put('admin/articles/{keyword}/{parent_folder}/{search_is_on?}', 'AdminArticlesController@update');
});

Route::group(['middleware' => 'locale'], function() {
    Route::put('admin/articles/{keyword}/{parent_folder}/{search_is_on?}', 'AdminArticlesController@update');
});

//End of Folders

//Articles
Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/article/create/{parent_keyword}/{show_invisible?}/{sorting_mode?}/{folders_or_articles_first?}', 
               'AdminArticleController@create');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/article/create/{parent_keyword}/{show_invisible?}/{sorting_mode?}/{folders_or_articles_first?}', 
               'AdminArticleController@create');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::post('admin/article', 'AdminArticleController@store');
});

Route::group(['middleware' => 'locale'], function() {
    Route::post('admin/article', 'AdminArticleController@store');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::get('admin/article/{parent_keyword}/edit/{keyword}/{show_invisible?}/{sorting_mode?}/{folders_or_articles_first?}', 
               'AdminArticleController@edit');
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('admin/article/{parent_keyword}/edit/{keyword}/{show_invisible?}/{sorting_mode?}/{folders_or_articles_first?}', 
               'AdminArticleController@edit');
});

Route::group(['prefix' => 'ru', 'middleware' => 'locale'], function() {
    Route::put('admin/article/{keyword}', 'AdminArticleController@update');
});

Route::group(['middleware' => 'locale'], function() {
    Route::put('admin/article/{keyword}', 'AdminArticleController@update');
});
//End of Articles

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