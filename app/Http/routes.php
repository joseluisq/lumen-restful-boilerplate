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

/**
 * API v1
 */
$app->group(['prefix' => 'api/v1', 'namespace' => 'App\Http\Controllers'], function($app) {

  /**
   * Book
   */
  $app->get('book', 'BooksController@all');
  $app->get('book/{id}', 'BooksController@get');
  $app->post('book', 'BooksController@add');
  $app->put('book/{id}', 'BooksController@put');
  $app->delete('book/{id}', 'BooksController@remove');
});
