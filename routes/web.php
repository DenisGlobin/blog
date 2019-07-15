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

Auth::routes();
Auth::routes(['verify' => true]);

Route::get('/', 'ArticleController@index')->name('articles');
Route::get('/{id}', 'ArticleController@showArticle')->name('article')->where(['id' => '[0-9]+']);


// User routes
Route::get('/profile', 'UserController@index')->name('profile')->middleware('auth','verified');
// Admin routes
Route::get('/profiles', 'AdminController@index')->name('admin.profiles')->middleware('auth', 'admin');
