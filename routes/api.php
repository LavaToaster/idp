<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'oauth'], function(\Illuminate\Routing\Router $router) {
    $router->post('token', 'OAuthController@issueAccessToken');
});

Route::group(['prefix' => 'saml', 'as' => 'saml.'], function(\Illuminate\Routing\Router $router) {
    $router->get('idp/metadata', ['as' => 'idp.metadata','uses' => 'SAMLController@metadata']);
    $router->post('idp/sso', ['as' => 'idp.sso','uses' => 'SAMLController@sso']);
    $router->post('idp/slo', ['as' => 'idp.slo','uses' => 'SAMLController@slo']);
});