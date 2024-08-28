<?php

use Illuminate\Support\Facades\Route;


// admin login


Route::get('admin_panel/login', 'AdminLogin@login');
Route::post('admin_panel/login', 'AdminLogin@login_post')->name('admin_panel.login');
Route::get('admin_panel/logout', 'AdminLogin@logout')->name('admin_panel.logout');






