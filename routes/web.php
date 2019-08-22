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
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', 'UserController@index')->name('profile');
    Route::post('/profile', 'UserController@editUserInfo')->name('edit.profile');
    Route::get('/change/email/{token}', 'UserController@confirmedEmailChange')->name('change.email')->where(['token' => '[0-9A-Za-z]+']);
    Route::get('/user/{id}', 'UserController@getUserInfo')->name('user.info')->where(['id' => '[0-9]+']);
    Route::get('/my/articles', 'UserController@getMyArticles')->name('my.articles');
    Route::get('/article/add', 'ArticleController@showAddArticleForm')->name('show.addarticle.form');
    Route::post('/article/add', 'ArticleController@addNewArticle')->name('add.article');
    Route::post('/comment', 'ArticleController@addComment')->name('add.comment');
});
// Admin routes
Route::get('/profiles', 'AdminController@index')->name('admin.profiles')->middleware('auth', 'admin');
