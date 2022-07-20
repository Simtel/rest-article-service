<?php

/** @var Router $router */

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

use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], callback: static function () use ($router) {
    $router->get(uri: 'articles/{id}', action: ['as' => 'article', 'uses' => 'ArticleController@show']);
    $router->post(uri: 'articles/list', action: ['as' => 'article.lists', 'uses' => 'ArticleController@showlist']);
    $router->post(uri: 'articles/create', action: ['as' => 'article.create', 'uses' => 'ArticleController@create']);
    $router->put(uri: 'articles/{id}', action: ['as' => 'article.update', 'uses' => 'ArticleController@update']);
    $router->delete(uri: 'articles/{id}',action: ['as' => 'article.delete', 'uses' => 'ArticleController@delete']);


    $router->post(uri: 'tags/create', action: ['as' => 'tag.create', 'uses' => 'TagController@create']);
    $router->put(uri: 'tags/{id}', action: ['as' => 'tag.update', 'uses' => 'TagController@update']);
});
