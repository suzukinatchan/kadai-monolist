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

Route::get('/', 'WelcomeController@index');

//ユーザー登録
Route::get('signup','Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup','Auth\RegisterController@register')->name('signup.post');

//ログイン認証
//LoginControllerには protected $redirectTo = '/';書いてあるから
//下の三つのルートは全部場合分けされたトップページに飛ばされるのか？
//get.loginはログイン登録ページに飛ばされるはずやろ。おかしくないか？
Route::get('login','Auth\LoginController@showLoginForm')->name('login');
Route::post('login','Auth\LoginController@login')->name('login.get');
Route::get('logout','Auth\LoginController@logout')->name('logout.get');

Route::group(['middleware'=>['auth']],function(){
    //楽天APIを使った検索結果を表示するページ（create）のみを作成する。
    //検索したものをすべて保存する必要はなく共有したいものだけを保存するので
    //次のwant,have機能の実装の時にItemを保存する。
    Route::resource('items','ItemsController',['only'=>['create','show']]);
    Route::post('want', 'ItemUserController@want')->name('item_user.want');
    Route::delete('want', 'ItemUserController@dont_want')->name('item_user.dont_want');
    Route::post('have', 'ItemUserController@have')->name('item_user.have');
    Route::delete('have', 'ItemUserController@dont_have')->name('item_user.dont_have');
    Route::resource('users', 'UsersController', ['only' => ['show']]);
});