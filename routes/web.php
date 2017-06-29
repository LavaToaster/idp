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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'saml', 'as' => 'saml.'], function(\Illuminate\Routing\Router $router) {
    $router->get('idp/metadata', ['as' => 'idp.metadata','uses' => 'SAMLController@metadata']);

    $router->group(['middleware' => 'auth'], function(\Illuminate\Routing\Router $router) {
        $router->match(['get', 'post'], 'idp/sso', ['as' => 'idp.sso', 'uses' => 'SAMLController@sso']);
        $router->get('idp/init', ['as' => 'idp.init', 'uses' => 'SAMLController@init']);
        $router->post('idp/slo', ['as' => 'idp.slo', 'uses' => 'SAMLController@slo']);
    });
});