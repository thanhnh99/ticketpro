<?php

use Illuminate\Http\Request;
// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::post('/event/{id}/validate-tickets', 'BookingController@validateTickets');
Route::prefix('/event/{eventId}')->group(function ()
{
    // Route::post('/validate-tickets', 'BookingController@validateTickets')->name('validateTicket');
    // Route::post('/validate-order', 'BookingController@validateOrder');
    Route::post('/notify-payment', 'BookingController@getIPN')->name('notify-payment');
});