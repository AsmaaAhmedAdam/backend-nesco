<?php

use App\Mail\Contact_usMail;
use App\Models\Messages;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::get('/', function() {
    return view('welcome');
});



