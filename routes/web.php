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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'Controller@getHomePage');

Route::get('/taxa/{taxon}', 'TaxonController@getTaxon');
Route::get('/keys/{key}', 'TaxonController@getKey');

Route::get('/search', 'SearchController@search');

Route::get('apidocs', function() {
    return view('swagger');
});
