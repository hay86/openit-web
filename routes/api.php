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

Route::group(['middleware' => ['auth:api'], 'namespace' => 'API'], function() {
    Route::get('/user', 'UserAPI@show');
    Route::get('/express/{id}', 'ExpressAPI@show');
    Route::get('/holidays/{year}', 'ExpressAPI@holidays');
    Route::get('/payment/{order}', 'PaymentAPI@query');
    Route::get('/refund/{order}', 'PaymentAPI@queryRefund');
});
