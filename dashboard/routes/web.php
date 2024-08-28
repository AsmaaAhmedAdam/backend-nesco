<?php

use Illuminate\Support\Facades\Route;


//////////// Login

require_once __DIR__ . '/login.php';


//////////// Admin

Route::group(['middleware' => ['AuthAdmin:admin','Admin_Language'] , 'namespace' => 'Admin' , 'prefix' => 'admin_panel'],function () {

    require_once __DIR__ . '/admin.php';

});

Route::get('admin_panel/{lang}', 'Admin\AdminController@change_lang');

//////////// User

Route::group(['middleware' => ['AuthUser:user'] , 'namespace' => 'User' , 'prefix' => '/'],function () {

    require_once __DIR__ . '/user.php';

});

//////////// Gest

require_once __DIR__ . '/gest.php';










// admin password reset routes

Route::prefix('admin_panel')->group(function () {

    Route::post('/password/email','Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin_panel.password.email');
    Route::get('/password/reset','Auth\AdminForgotPasswordController@showLinkRequestForm');
    Route::post('/password/reset','Auth\AdminResetPasswordController@reset')->name('admin_panel.password.request');
    Route::get('/password/reset/{token}','Auth\AdminResetPasswordController@showResetForm')->name('admin_panel.password.reset');

});





Route::get('paytabs_callback', 'HomeController@callback_url');

