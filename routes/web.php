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
Route::get('/user/{id}/article', 'ArticleController@getUserArticles')->name('user.article')->where(['id' => '[0-9]+']);
Route::get('/articles/from/{month}/{year}', 'ArticleController@getArticlesFromMonth')->name('articles.from')->where(['month' => '[0-9]+' , 'year' => '[0-9]+']);

// User routes
Route::middleware(['auth', 'verified'])->group(function () {
    // User's profile
    Route::get('/profile', 'UserController@index')->name('profile');
    Route::post('/profile/edit', 'UserController@editUserInfo')->name('profile.edit');
    Route::get('/change/email/{token}', 'UserController@confirmedEmailChange')->name('change.email')->where(['token' => '[0-9A-Za-z]+']);
    Route::get('/user/{id}', 'UserController@getUserInfo')->name('user.info')->where(['id' => '[0-9]+']);
    // Articles
    Route::get('/article/new', 'ArticleController@showAddArticleForm')->name('show.addarticle.form');
    Route::post('/article/add', 'ArticleController@addNewArticle')->name('article.add');
    Route::get('/article/edit/{id}', 'ArticleController@showEditArticleForm')->name('show.editarticle.form')->where(['id' => '[0-9]+']);
    Route::post('/article/edit', 'ArticleController@updateArticle')->name('article.edit');
    Route::delete('/article/delete', 'ArticleController@deleteArticle')->name('article.delete');
    // Comments
    Route::post('/comment/add', 'CommentController@addComment')->name('comment.add');
    Route::delete('/comment/delete', 'CommentController@deleteComment')->name('comment.delete');
    Route::post('/comment/edit', 'CommentController@updateComment')->name('comment.edit');
});
// Admin routes
Route::get('/profiles', 'AdminController@index')->name('admin.profiles')->middleware('auth', 'admin');
