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

Route::get('/', function () {
    return view('welcome');
});


Auth::routes(['verify' => true]);
Route::get('login/facebook', 'Auth\LoginController@redirectToProvider')->name('login_facebook');
Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderCallback');


Route::get('/home', 'HomeController@index')->name('home');


Route::get('/test-verify', function () {
    return 'Verify route';
})->middleware('verified');


Route::get('/recipe', 'RecipeController@lists')->name('recipe.list');
Route::resource('recipe', 'RecipeController')->except(['index']);
