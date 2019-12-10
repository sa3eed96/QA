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


Auth::routes();

Route::get('/',  'HomeController@index')->name('home');
Route::resource('question','QuestionController')->except('show');
Route::get('/question/{slug}','QuestionController@show')->name('question.show');
Route::resource('question.answer','AnswerController')->only(['index','store','edit','update','destroy']);
Route::post('answers/{answer}/accept','AcceptAnswerController')->name('answer.accept');
Route::post('questions/{question}/favourites','FavouritesController@store')->name('question.favourite');
Route::delete('questions/{question}/favourites','FavouritesController@destroy')->name('question.unfavourite');
Route::post('questions/{question}/vote','VoteQuestionController');
Route::post('answers/{answer}/vote','VoteAnswerController');
Route::resource('question.images','ImageQuestionController')->only(['index','destroy']);
Route::resource('answer.images','ImageAnswerController')->only(['index','destroy']);
Route::get('tags','TagController@index');
Route::resource('notifications','NotificationController')->only(['index']);
Route::patch('/notifications','NotificationController@update')->name('notifications.update');
Route::resource('user','UserController')->only(['show','update','destroy']);



