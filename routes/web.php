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

Route::get('/',  'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');

Route::resource('question','QuestionController')->except('show');
Route::resource('question.answer','AnswerController')->only(['index','store','edit','update','destroy']);
Route::get('/question/{slug}','QuestionController@show')->name('question.show');
Route::post('answers/{answer}/accept','AcceptAnswerController')->name('answer.accept');
Route::post('questions/{question}/favourites','FavouritesController@store')->name('question.favourite');
Route::delete('questions/{question}/favourites','FavouritesController@destroy')->name('question.unfavourite');
Route::post('questions/{question}/vote','VoteQuestionController');
Route::post('answers/{answer}/vote','VoteAnswerController');