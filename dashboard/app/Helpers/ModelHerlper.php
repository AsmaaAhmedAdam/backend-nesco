<?php


use App\Models\Admin;
use App\Models\Categories;
use App\Models\Cities;
use App\Models\Size;



if (! function_exists('All_Admin')) {

    function All_Admin()
    {
        return Admin::pluck('name','id');
    }
}




if (! function_exists('H_Category')) {

    function H_Category($lang = 'en')
    {
        return Categories::where('type', Categories::TYPE['products'])->pluck($lang.'_title','id');
    }
}

if (! function_exists('M_Category')) {

    function M_Category($lang = 'en')
    {
        return Categories::where('type', Categories::TYPE['menu'])->pluck($lang.'_title','id');
    }
}



if (! function_exists('H_Sizes')) {

    function H_Sizes()
    {
        return Size::pluck('title','id');
    }
}




if (! function_exists('H_Cities')) {

    function H_Cities($lang = 'en')
    {
        return Cities::where('status',1)->pluck($lang.'_name','id');
    }
}




