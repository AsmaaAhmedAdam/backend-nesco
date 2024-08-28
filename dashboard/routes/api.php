<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// laravel
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
    
    
});

 

// register
Route::get('register', function() {
    return response()->json([ 'status' => false, 'errNum' => '404', 'msg' => 'bad request' ]);
});
Route::post('register', 'Api\AuthController@register');

// firebase
// Route::get('firebase', function() {
//     return response()->json(['status' => false, 'errNum' => '404', 'msg' => 'bad request']);
// });
// Route::post('firebase', 'Api\AuthController@firebaseadd');






// login
Route::get('login', function() {
    return response()->json([ 'status' => false, 'errNum' => '404', 'msg' => 'bad request' ]);
});
Route::post('login','Api\AuthController@login');
Route::post('social_login','Api\AuthController@social_login');


// get-token
// Route::post('get-token','Api\AuthController@get_token');
// ----------------------- Start Gest User  ----------------------



Route::group(['middleware' => ['checkPassword','CheckLang'],'namespace' => 'Api'], function() {
    // ----------------------- Start All Home And Search Data  ----------------------
  
    
        
        // popular-categories
        Route::get('popular-categories', 'HomeController@popular_categories');
        // categories
        Route::get('categories/{id?}', 'HomeController@categories');
        // some best selling
        Route::get('some-best-selling', 'HomeController@some_best_selling');
        // all best selling
        Route::get('all-best-selling', 'HomeController@all_best_selling');
        // some-new-arrival-products
        Route::get('some-new-arrival-products', 'HomeController@some_new_arrival_products');
        // new-arrival-products
        Route::get('new-arrival-products', 'HomeController@new_arrival_products');
         // Setting
        Route::get('setting','HomeController@setting');
        // Faq
        Route::get('faq','HomeController@faq');
        // Slider
        Route::get('slider','HomeController@slider');
        // view-product
        Route::get('view-product/{id}', 'HomeController@view_product');
        // ->middleware('AuthUser:user-api');
        // all-pages
        Route::get('all-pages', 'HomeController@allPages');
        // page-details
        Route::get('page-details/{page_id}', 'HomeController@pageDetails');
        // all-product
        Route::get('all-products', 'HomeController@all_product');
        // menu
        Route::get('menu', 'HomeController@menu');
        // menu-product-details
        Route::get('menu-product-detail/{menu_id}', 'HomeController@menuDetails');
        //cities
        Route::get('cities', 'HomeController@cities');
        
        Route::post('careers/send','HomeController@careers');
        //franchising
        Route::post('franchising/send','HomeController@franchising');
        //corporate
        Route::post('corporate/send','HomeController@corporatelogin');
        
        //payment-callback
        Route::post('payment-callback', 'CartController@paymentCallback');
        //payment_operation
        Route::get('payment_invoice','CartController@get_invoice');
        // track order
        Route::post('order/track','CartController@track_order');
    // ----------------------- End All Users Data  ----------------------

    // ----------------------- Start Search Data  ----------------------
        // order-search
        Route::get('search', 'SearchController@search');
    // ----------------------- End Search Data  ----------------------

    // ------------------------ Start Reset Password  ----------------------
    Route::post('forget-password', 'ResetPasswordController@forget_password');
    Route::post('reset-password', 'ResetPasswordController@reset_password');
    // ----------------------- End Reset Password  ----------------------
    
    // ----------------------- Start Auth User  ----------------------
    Route::group(['middleware' => ['AuthUser:user-api']], function() {
    // ----------- start profile & logout and get user data -------------------
        // update-profile
        Route::get('update-profile', function() {
            return response()->json([ 'status' => false, 'errNum' => '404', 'msg' => 'bad request' ]);
        });
        Route::post('update-profile', 'AuthController@update_profile');
        Route::get('logout', function() {
            return response()->json([ 'status' => false, 'errNum' => '404', 'msg' => 'bad request' ]);
        });
        Route::post('logout', 'AuthController@logout');
        // get_user
        Route::get('get_user', 'AuthController@get_user');
    // ----------- end logout and get user data -------------------

    // ---------------------- start all user personal data ----------------------
        Route::group(['prefix' => 'user'], function() {
            // add address
            Route::post('address/add','CartController@addAdress');
            // add-to-cart
            Route::get('add-to-cart', function() {
                return response()->json([ 'status' => false, 'errNum' => '404', 'msg' => 'bad request' ]);
            });
            Route::post('add-to-cart','CartController@addToCart');
            // update-cart
            Route::get('update-cart', function() {
                return response()->json([ 'status' => false, 'errNum' => '404', 'msg' => 'bad request' ]);
            });
            Route::post('update-cart','CartController@UpdateCart');
            // remove-from-cart
            Route::post('remove-from-cart/{row_id}','CartController@removeFromCart');
            // remove-cart
            Route::get('remove-cart','CartController@removeCart');
            // add-to-favorite
            Route::post('add-to-favorite/{product_id}','CartController@addToFavorite');
            // cart
            Route::get('cart','CartController@cart');
            // favorite
            Route::get('favorite','CartController@favorite');
            // checkout
            Route::post('checkout', function() {
                return response()->json([ 'status' => false, 'errNum' => '404', 'msg' => 'bad request' ]);
            });
            Route::get('checkout','CartController@checkout');
            // payment
            Route::post('payment', function() {
                return response()->json([ 'status' => false, 'errNum' => '404', 'msg' => 'bad request' ]);
            });
            Route::get('payment','CartController@payment');
            // orders
            Route::get('my-invoices/{id?}', 'CartController@my_invoices');
            // coupon
            Route::get('coupon', 'CartController@coupon');
            // get-user-reviews
            Route::get('user-reviews','ReviewsController@getUserReviews');
            // add-review
            Route::get('add-review', function() {
                return response()->json([ 'status' => false, 'errNum' => '404', 'msg' => 'bad request' ]);
            });
            Route::post('add-review','ReviewsController@addReview');
            // update-review
            Route::get('update-review', function() {
                return response()->json([ 'status' => false, 'errNum' => '404', 'msg' => 'bad request' ]);
            });
            Route::post('update-review','ReviewsController@UpdateReview');
            // notifications
            Route::get('notifications/{id?}', 'ReviewsController@notifications');
        });
    // ----------------------  end all user data ----------------------
    });
    // ----------------------- End Auth User  ----------------------
});
// ----------------------- End Gest User  ----------------------





Route::get('find-transaction/{tap_id}','Api\Tab_APiController@find_transaction');
Route::get('redirect-url','Api\Tab_APiController@redirect_function');
Route::post('post-url','Api\Tab_APiController@post_function');
Route::get('payment_operation', 'Api\Tab_APiController@payment_operation');
/*
Route::post('return_url', 'Api\Paytabs_APiController@return_url');
Route::get('payment_operation', 'Api\Paytabs_APiController@payment_operation');
*/
/*
Route::get('payment/callback', 'Api\MyFatoorah_APiController@callback');
Route::get('payment/error', 'Api\MyFatoorah_APiController@error');
Route::get('payment_operation', 'Api\MyFatoorah_APiController@payment_operation');
*/
Route::get('sendNotification', 'NotificationController@sendNotification')->name('send.notification');
