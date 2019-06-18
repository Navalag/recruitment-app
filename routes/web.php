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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/applicant/{id}/send-email', 'ApplicantController@sendEmail');
Route::resource('applicant', 'ApplicantController');

Route::resource('vacancy', 'VacanciesController');

Route::get('/start-test/{key}', 'TestTasksController@startTestTask');
Route::post('/start-test', 'TestTasksController@beginTestTask');

Route::get('/finish-test/{key}', 'TestTasksController@finishTestTask');
Route::post('/finish-test', 'TestTasksController@recordFinishTestTaskTime');

Route::get('/gmail-settings', 'GmailSettingsController@index');
Route::get('/oauth/gmail', 'GmailSettingsController@auth');
Route::get('/oauth/gmail/callback', 'GmailSettingsController@createToken');
Route::get('/oauth/gmail/logout', 'GmailSettingsController@logout');

Route::get('/gmail/show-messages', 'GmailController@showMessages');
