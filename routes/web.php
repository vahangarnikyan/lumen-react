<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Auth;


$router->get('/admin{any:.*}', function () {
    return view('admin.index');
});

$router->group(['namespace' => 'Front'], function () use ($router) {
    $router->get('/test', ['as' => 'front.test.index', 'uses' => 'TestController@index']);
    $router->get('/sitemap', ['as' => 'front.sitemap.index', 'uses' => 'SitemapController@index']);
    $router->get('/login', ['as' => 'front.login.get', 'uses' => 'AuthController@loginGet']);
    $router->post('/login', ['as' => 'front.login.post', 'uses' => 'AuthController@loginPost']);
    $router->get('/register', ['as' => 'front.register.get', 'uses' => 'AuthController@registerGet']);
    $router->post('/register', ['as' => 'front.register.post', 'uses' => 'AuthController@registerPost']);
    $router->get('/forgot', ['as' => 'front.forgot.get', 'uses' => 'AuthController@forgotPasswordGet']);
    $router->post('/forgot', ['as' => 'front.forgot.post', 'uses' => 'AuthController@forgotPasswordPost']);
    $router->get('/reset', ['as' => 'front.reset.get', 'uses' => 'AuthController@resetPasswordGet']);
    $router->post('/reset', ['as' => 'front.reset.post', 'uses' => 'AuthController@resetPasswordPost']);
    
    $router->get('/profile', [
        'as' => 'front.profile',
        'uses' => 'ProfileController@index',
        'middleware' => ['auth', 'redirectIfUnconfirmed']
    ]);

    $router->get('/logout', [
        'as' => 'front.logout',
        'uses' => 'ProfileController@logout',
        'middleware' => ['auth']
    ]);

    $router->get('/pending-confirmation', [
        'as' => 'front.pending-confirmation',
        'uses' => 'ProfileController@pendingConfirmation',
        'middleware' => ['auth', 'redirectIfConfirmed']
    ]);

    $router->get('/send-confirmation', [
        'as' => 'front.send-confirmation',
        'uses' => 'ProfileController@sendConfirmation',
        'middleware' => ['auth', 'redirectIfConfirmed']
    ]);

    $router->get('/confirm', [
        'as' => 'front.confirm',
        'uses' => 'ProfileController@confirm',
        'middleware' => ['auth', 'redirectIfConfirmed']
    ]);

    $router->get('/{slug}', [
        'as' => 'front.page',
        'uses' => 'PageController@show'
    ]);

    $router->get('/', [
        'as' => 'front.index',
        'uses' => 'HomeController@index',
        'middleware' => ['auth', 'redirectIfUnconfirmed']
    ]);
});