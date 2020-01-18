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

Route::get('/', 'QuestionsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::resource('questions','QuestionsController')->except('show');
Route::get('/questions/{slug}','QuestionsController@show')->name('questions.show');


// answers 

Route::resource('questions.answers','AnswersController')->except(['index','create','show']);

Route::post('/answers/{answer}/accept','AcceptAnswersController')->name('answers.accept');


Route::post('/questions/{question}/favorites', 'FavoriteController@store')->name('questions.favorite');
Route::delete('/questions/{question}/favorites', 'FavoriteController@destroy')->name('questions.unfavorite');


// votes 


Route::post('/questions/{question}/vote','VoteQuestionController');
 Route::post('/answers/{answer}/vote','VoteAnswerController');