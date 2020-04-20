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

use Illuminate\Support\Facades\Route;

/**Event */
Route::get('/','HomeController@getIndex')->name('home');
Route::prefix('event')->group(function(){
    Route::get('/all','HomeController@getAll')->name('event.all');
    Route::POST('/search','HomeController@getSearch')->name('event.search');
    Route::get('/{eventId}','HomeController@getEventDetail')->name('event.detail');
    Route::prefix('{eventId}')->group(function(){
        Route::get('booking','HomeController@bookingDetail')->name('booking');
        Route::post('validate-ticket',"BookingController@validateTicket")->name('validate-ticket');
        Route::get('/create-payment','PaymentController@createPayment')->name('create_payment');
        Route::post('/post-payment','PaymentController@postPayment')->name('post_payment');

    });
    
});
/**Login logout*/
Route::get('/auth/redirect/{provider}', 'SocialController@redirect')->name("login");
Route::get('/callback/{provider}', 'SocialController@callback')->name('callbackLogin');
Route::get('/logout','SocialController@logout')->name('logout');

/**User */
Route::prefix('account/')->group(function (){
    Route::get('/','UserController@getProfile')->name('get_profile');
    Route::get('/create-event','UserController@getCreateEvent')->name('event.create');
    Route::post('/store-event','UserController@storeEvent')->name('event.store');
    Route::get('/buy-history','UserController@getBuyHistory')->name('buy_history');
    Route::get('/created-event-list','UserController@getCreatedEventList')->name('created_event_list');
    Route::post('/update-profile', 'UserController@updateProfile')->name('update_profile');
    Route::get('/buy-history/{eventId}', 'UserController@buyEventDetail')->name('ticketByDetails');
    Route::get('/event-buy-detail/{eventId}','UserController@getEventBuyDetail')->name('eventBuyDetail');

});


// Route::get('/create-payment','PaymentController@createPayment')->name('create_payment');
// Route::post('/post-payment','PaymentController@postPayment')->name('post_payment');
Route::get('/vnpay-return','PaymentController@vnpayReturn')->name('vnpay-return');

// // Route::get('/event-detail/{eventId}','HomeController@getEventDetail')->name('event_detail');
// Route::get('/event/all','HomeController@getAll')->name('all-event');
// Route::get('/booking/{eventId}','HomeController@bookingDetail')->name('booking');

// //TODO::group router
// Route::get('/sport','HomeController@getSportEvent')->name('sport');
// Route::get('/music','HomeController@getMusicEvent');
// Route::get('/conference','HomeController@getConferenceEvent');
// // Route::get('/search','HomeController@getSearch')->name('search');


//Auth
/**
 * Route cho Admin
 */
Route::prefix('admin')->group(function(){
    /**
     * Gom nhóm các route cho phần admin
     */

    /**
     * URL: localhost/admin/
     * Route mặc định của admin
     */
    Route::get('/','Admin\AdminController@index')->name('admin.dashboard');


    /**
     *  URL: localhost/admin/dashboard
     * Route đăng nhập thanh công
     */
    Route::get('/dashboard','Admin\AdminController@index')->name('admin.dashboard');


    /**
     *  URL: localhost/admin/login
     * Route trả về để đăng nhập admin
     */
    Route::get('/login','Admin\LoginController@login')->name('admin.auth.login');


    /**
     *  URL: localhost/admin/login
     * Route xử lí quá trình đăng nhập của admin
     */
    Route::post('/login','Admin\LoginController@loginAdmin')->name('admin.auth.loginadmin');

    /**
     *  URL: localhost/admin/logout
     * Route dùng để đăng xuất
     */
    Route::POST('/logout','Admin\LoginController@logout')->name('admin.auth.logout');

    Route::get('/event-list',"Admin\AdminController@getListEvent")->name('admin.listEvent');
    Route::get('/booking',"Admin\AdminController@getListBooking")->name('admin.listBooking');
    Route::get('/booking/{bookingId}','@Admin\AdminController@getBookingDetail')->name('admin.bookingDetail');
});


// /**
//  * Route cho phần đăng nhập bằng google
//  */
// Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
// Route::get('/callback/{provider}', 'SocialController@callback')->name('callbackLogin');
// Route::get('/logout','SocialController@logout')->name('logout');

// /**
//  * Rout xủ lí Event
//  */
// Route::prefix('event/{eventId}')->group(function(){
//     Route::get('/','HomeController@getTicketDetail')->name('event-detail');
//     Route::get('/ticket-booking', 'BookingController@showSelectTicket')->name('choose-ticket');
//     Route::get('/checkout', 'BookingController@showEventCheckout')->name('event-checkout');
//     Route::post('/validate-tickets', 'BookingController@validateTickets')->name('validateTicket');
//     Route::post('/validate-order', 'BookingController@validateOrder')->name('validateOrder');
//     Route::get('/booking-complete', 'BookingController@completePayment')->name('complete-payment');
// });
// // Route::get('/booking/complete', 'BookingController@completePayment');
// // Route::get('/booking/purchase', 'BookingController@purchase');
// // Route::get('/event/{eventId}/ticket-booking/{userId}', 'BookingController@chooseTicket'); 