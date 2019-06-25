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

Route::get('/start-test/{key}', 'TestTasks\StartTestTaskController@index');
Route::post('/start-test', 'TestTasks\StartTestTaskController@beginTask');

Route::get('/finish-test/{key}', 'TestTasks\FinishTestTaskController@index');
Route::post('/finish-test', 'TestTasks\FinishTestTaskController@finishTask');

Route::get('/gmail-settings', 'GmailSettingsController@index');
Route::get('/oauth/gmail', 'GmailSettingsController@auth');
Route::get('/oauth/gmail/callback', 'GmailSettingsController@createToken');
Route::get('/oauth/gmail/logout', 'GmailSettingsController@logout');
