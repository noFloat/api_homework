<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::any('/test', function () {
    return view('test.index');
});
Route::any('/verify', 'Commen\MessageController@sendMessage');
Route::any('/checkVerify', 'Commen\MessageController@checkVerify');
Route::post('oauth/access_token', function() {
 return Response::json(Authorizer::issueAccessToken());
});

//Create a test user, you don't need this if you already have.
Route::post('/register','Content\UserController@register');
Route::post('/login','Content\UserController@login');
Route::post('/update','Content\UserController@update');
Route::get('/search','Content\UserController@search');

Route::post('/repairer/register','Content\RepairerController@register');
Route::post('/repairer/login','Content\RepairerController@login');
Route::post('/repairer/update','Content\RepairerController@update');
Route::get('/repairer/search','Content\RepairerController@search');

Route::any('/domiantionlist','Content\UserdominationController@search');

Route::any('/adddomiantion','Content\UserdominationController@add');

Route::any('/repairlist/create','Content\RepairlistController@insert');

Route::any('/repairlist/evaluate','Content\RepairlistController@evaluate');

Route::any('/repairlist/grab','Content\RepairlistController@grab');


Route::get('/building/search','Content\BuildingController@search');
Route::get('/building/energy_now','Content\BuildingController@energy_now');
Route::get('/building/goal','Content\BuildingController@searchGoal');
Route::get('/building/monitor','Content\BuildingController@monitor');
// /**
//  * Api
//  */
// $api = app('Dingo\Api\Routing\Router');

// //Show user info via restful service.
// $api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api) {
//     $api->get('users', 'UsersController@index');
//     $api->get('users/{id}', 'UsersController@show');
// });

// //Just a test with auth check.
// $api->version('v1', ['middleware' => 'api.auth'] , function ($api) {
//     $api->get('time', function () {
//         return ['now' => microtime(), 'date' => date('Y-M-D',time())];
//     });
// });