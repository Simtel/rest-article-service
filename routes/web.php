<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], callback: static function () use ($router) {
    $router->get('articles/{id}', action: ['as' => 'article', 'uses' => 'ArticleController@show']);
    $router->post('tags/create', ['as' => 'tag.create', 'uses' => 'TagController@create']);
    $router->put('tags/{id}', ['as' => 'tag.update', 'uses' => 'TagController@update']);
});
