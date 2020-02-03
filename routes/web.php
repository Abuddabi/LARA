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

use Illuminate\Support\Facades\Route;

//Front
Route::get('/', 'HomeController@index');
Route::get('/post/{slug}', 'HomeController@show')->name('post.show');
Route::get('/tag/{slug}', 'HomeController@tag')->name('tag.show');
Route::get('/category/{slug}', 'HomeController@category')->name('category.show');

//Registration
Route::group(['middleware' => 'guest'], function(){
  Route::get('/register', 'AuthController@registerForm');
  Route::post('/register', 'AuthController@register');
  Route::get('/login', 'AuthController@loginForm')->name('login');
  Route::post('/login', 'AuthController@login');
});

Route::group(['middleware' => 'auth'], function(){
  Route::get('/logout', 'AuthController@logout');
  Route::get('/profile', 'ProfileController@index');
  Route::post('/profile', 'ProfileController@store');
  Route::post('/comment', 'CommentsController@store');
});

//Admin
Route::group(['prefix'=>'admin', 'namespace'=>'Admin', 'middleware' => 'admin'], function(){
  Route::get('/', 'DashboardController@index');
  Route::resource('/categories', 'CategoriesController');
  Route::resource('/tags', 'TagsController');
  Route::resource('/users', 'UsersController');
  Route::resource('/posts', 'PostsController');
  Route::get('/comments', 'CommentsController@index')->name('comments.index');
  Route::get('/comments/toggle/{id}', 'CommentsController@toggle');
  Route::delete('/comments/{id}/destroy', 'CommentsController@destroy')->name('comments.destroy');
});

 