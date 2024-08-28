<?php

use Illuminate\Support\Facades\Route;


// Home
Route::get('/clearcache', function(Request $request) {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
        Artisan::call('route:clear');
return  'cleared';

});
Route::get('/', 'AdminController@home');


// Admins

Route::resource('admin','AdminController',
['names' => 'admin_panel.admin']);

// Admin Update Password
Route::patch('admin/update_password/{id}', 'AdminController@UpdatePass')->name('admin_panel.admin.UpdatePass');

Route::get('admin/destroy/{id}','AdminController@destroy');




Route::get('notifications', 'AdminController@notifications');
Route::get('update_notifications', 'AdminController@update_notifications');



// Setting

Route::get('setting','SettingController@setting');
Route::post('setting','SettingController@update_setting');



//Pages


Route::resource('site_pages','PagesController');

Route::get('site_pages/create','PagesController@create');
Route::post('site_pages/store','PagesController@store');








// Categories

Route::resource('product_categories','CategoryProductsController',
['names' => 'admin_panel.categories']);
Route::get('product_categories/destroy/{id}','CategoryProductsController@destroy');
Route::get('product_categories/popularity/{id}','CategoryProductsController@popularity');

Route::resource('menu_categories','MenuCategoryController');
Route::get('menu_categories/destroy/{id}','MenuCategoryController@destroy');
Route::get('menu_categories/popularity/{id}','MenuCategoryController@popularity');
// Product

Route::resource('products','ProductsController',
['names' => 'admin_panel.products']);

Route::get('products/destroy/{id}','ProductsController@destroy');
Route::get('products/popularity/{id}','ProductsController@popularity');

//menu
Route::resource('menu','MenuController');
Route::get('menu/popularity/{id}','MenuController@popularity');
Route::get('menu/destroy/{id}','MenuController@destroy');
// Slider

Route::resource('slider','SliderController',
['names' => 'admin_panel.slider']);

Route::get('slider/destroy/{id}','SliderController@destroy');


// Faq

Route::resource('faq','FaqController',
['names' => 'admin_panel.faq']);

Route::get('faq/destroy/{id}','FaqController@destroy');







// Cities

Route::resource('cities','CitiesController',
['names' => 'admin_panel.cities']);

Route::get('cities/un_active/{id}','CitiesController@un_active');
Route::get('cities/active/{id}','CitiesController@active');



// Users

Route::resource('users','UsersController',
['names' => 'admin_panel.users']);

Route::get('users/destroy/{id}','UsersController@destroy');

Route::get('users/inovice_details/{user_id}/{invoice_id}','UsersController@inovice_details');

Route::post('users/export-excel' , 'UsersController@exportExcel')->name('users.download-excel');


// Invoices

Route::get('invoices','InvoicesController@all_invoices');
Route::get('invoice_details/{serial_no}','InvoicesController@invoice_details');

Route::get('invoice_status','InvoicesController@update_invoice_status');

Route::get('invoices/destroy/{id}','InvoicesController@delete_invoice');

Route::get('invoices/print/{id}','InvoicesController@print');




// Coupon

Route::resource('coupon','CouponController',
['names' => 'admin_panel.coupon']);

Route::get('coupon_accept/{id}','CouponController@coupon_accept');

Route::get('coupon_refused/{id}','CouponController@coupon_refused');

Route::get('coupon/orders/{id}','CouponController@coupon_orders');



// Reviews

Route::get('reviews','ReviewsController@reviews');

Route::get('review_accept/{id}','ReviewsController@reviews_accept');

Route::get('review_refused/{id}','ReviewsController@reviews_refused');




// policy

Route::get('policy','SettingController@policy');
Route::post('policy','SettingController@update_policy');



