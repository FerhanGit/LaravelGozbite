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

Route::resource('recipe', 'RecipeController')->except(['index']);


Route::get('/recipe/category/{category}/user/{user}', 'RecipeController@lists')->name('recipe.category.user.list');
Route::get('/recipe/category/{category}', 'RecipeController@listsByCategory')->name('recipe.category.list');
Route::get('/recipe/user/{user}', 'RecipeController@listsByUser')->name('recipe.user.list');
Route::get('/recipe', 'RecipeController@lists')->name('recipe.list');

Route::delete('/recipe/image/delete/{recipeImage}', 'RecipeController@deleteImage')->name('recipe.delete.image');

Route::get('storage/{filename}', function ($filename)
{
    $path = storage_path('public/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});