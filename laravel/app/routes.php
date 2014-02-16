<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//Route::get('/note/{id}', array('https', 'uses' => 'noteController@getNote'));
Route::get('/note/{id}/{key}', array('uses' => 'OneTimeNote\Controllers\NoteController@getNote'));
Route::post('/note', array('uses' => 'OneTimeNote\Controllers\NoteController@postNote'));