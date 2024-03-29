<?php

use Illuminate\Support\Facades\Route;

Route::get('join-minitutor', 'JoinMinitutorController@show');
Route::get('join-minitutor/status', 'JoinMinitutorController@status');
Route::post('join-minitutor', 'JoinMinitutorController@store');

Route::get('categories', 'CategoriesController@index');
Route::get('categories/popular', 'CategoriesController@popular');
Route::get('categories/{slug}', 'CategoriesController@show');

Route::post('follow/{minitutor_id}', 'FollowController@store');
Route::delete('follow/{minitutor_id}', 'FollowController@destroy');

Route::post('favorites/{post_id}', 'FavoritesController@store');
Route::delete('favorites/{post_id}', 'FavoritesController@destroy');

Route::get('articles', 'ArticlesController@index');
Route::get('articles/news', 'ArticlesController@news');
Route::get('articles/{slug}', 'ArticlesController@show');

Route::get('videos', 'VideosController@index');
Route::get('videos/news', 'VideosController@news');
Route::get('videos/{slug}', 'VideosController@show');

Route::get('feedback/{post_id}', 'FeedbackController@show');
Route::post('feedback/{post_id}', 'FeedbackController@store');

Route::post('comments/{post_id}', 'CommentsController@store');

Route::get('minitutors', 'MinitutorsController@index');
Route::get('minitutors/{username}', 'MinitutorsController@show');

Route::get('users', 'UsersController@index');
Route::get('users/most-points', 'UsersController@mostPoints');
Route::get('users/{idOrUsername}', 'UsersController@show');

Route::get('nuxt/home', 'NuxtController@home');
Route::get('nuxt/landing', 'NuxtController@landing');
