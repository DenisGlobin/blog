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
Route::get('/change/email/{token}', 'UserController@confirmedEmailChange')->name('change.email')->where(['token' => '[0-9A-Za-z]+']);

// User routes
Route::get('/profile', 'UserController@index')->name('profile')->middleware('auth','verified');
Route::post('/profile', 'UserController@editUserInfo')->name('edit.profile')->middleware('auth','verified');
Route::get('/user/{id}', 'UserController@getUserInfo')->name('user.info')->where(['id' => '[0-9]+'])->middleware('auth','verified');
Route::get('/my/articles', 'UserController@getMyArticles')->name('my.articles')->middleware('auth','verified');
Route::get('/article/add', 'ArticleController@showAddArticleForm')->name('show.addarticle.form')->middleware('auth','verified');
Route::post('/article/add', 'ArticleController@addNewArticle')->name('add.article')->middleware('auth','verified');
Route::post('/comment', 'ArticleController@addComment')->name('add.comment')->middleware('auth','verified');
// Admin routes
Route::get('/profiles', 'AdminController@index')->name('admin.profiles')->middleware('auth', 'admin');
