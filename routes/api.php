<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::get('/programmers', function (Request $request) {
//     return App\User::select(['id', 'name'])
//         ->with('activeSprints', 'activeSprints.project', 'activeSprints.project.owner')
//         ->get();
// });

// Route::group(['middleware' => 'admin'], function() {

// });

Route::get('/programmers', 'WorkersController@index');
Route::post('/workers', 'WorkersController@store');
Route::patch('/workers', 'WorkersController@update');
Route::delete('/workers/{id}', 'WorkersController@destroy');


Route::get('/sprints', 'SprintsController@index');
Route::post('/sprints','SprintsController@store');
Route::patch('/sprints', 'SprintsController@update');
Route::delete('/sprints/{id}', 'SprintsController@destroy');

Route::get('/customers', 'CustomersController@index');
Route::post('/customers', 'CustomersController@store');
Route::patch('/customers', 'CustomersController@update');
Route::delete('/customers/{id}', 'CustomersController@destroy');

Route::get('/projects', 'ProjectsController@index');
Route::post('/projects', 'ProjectsController@store');
Route::patch('/projects', 'ProjectsController@update');
Route::delete('/projects/{id}', 'ProjectsController@destroy');

Route::get('/payments', 'PaymentsController@index');
Route::post('/payments', 'PaymentsController@store');

Route::post('/statistics', 'StatisticsController@show');

Route::get('/currencies', function (Request $request) {
    return App\Currency::select(['id', 'name', 'symbol'])
        ->get();
});
