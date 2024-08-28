<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notifications;
use App\Models\Product;
use App\Models\Reviews;
use App\Models\User;

class ReviewsController extends Controller
{

    public function get_lang()
    {
        $lang = session()->get('admin_lang');

        if($lang == 'en' && $lang != null) {
            return $lang;
        } else {
            return 'ar';
        }
    }


    public function reviews()
    {
         $Item = Reviews::get();
         return view('admin.reviews.index',compact('Item'));
    }


    public function reviews_accept($id) {

        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }

        $reviews = Reviews::findOrFail($id);

        $product = Product::findOrFail($reviews->product_id);

        $reviews->update([ 'status' => 'accept' ]);

        $product_reviews_sum = Reviews::where('product_id',$reviews->product_id)->where('status','accept')->sum('value');
        $product_reviews_count = Reviews::where('product_id',$reviews->product_id)->where('status','accept')->select('id')->count();

        if($product_reviews_count > 0) {
            $calc = ( round($product_reviews_sum/$product_reviews_count) );
        } else {
            $calc = 0;
        }

        $product->update([
            'reviews' => $calc
        ]);

        /////////////////////////////////////////////////////

        Notifications::create([
            'add_by' => 'admin',
            'user_id' => 1,
            'send_to_type' => 'user',
            'send_to_id' => $reviews->user_id,
            'en_description' => 'congratulations your product review  is accepted ( '.$product->en_title.' )  ',
            'ar_description' => 'مبروووك تم قبول تقيم المنتج  ( '.$product->ar_title.' ) ',
            'url' => null,
            'seen' => 0,
            'type' => 'review',
            'item_id' => $reviews->id,
            'en_title' => 'accept product review',
            'ar_title' => ' قبول تقيم المنتج ',
        ]);

        $user = User::where('id',$reviews->user_id)->first();

        if($user != null && $user->device_token != null) {

            $users_device_token = [$user->device_token];
            $title = 'قبول تقيم المنتج';
            $body = 'مبروووك تم قبول تقيم المنتج  ( '.$product->ar_title.' ) ';

            Push_Notification($users_device_token,$title,$body);
        }

        /////////////////////////////////////////////////////

        if($lang == 'en') {
            return redirect()->back()->with('success','this review is accepted successfuly');
        } else {
            return redirect()->back()->with('success','تم قبول هذا التقيم بنجاح');
        }

    }



    public function reviews_refused($id) {

        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }

        $reviews = Reviews::findOrFail($id);

        $product = Product::findOrFail($reviews->product_id);

        $reviews->delete();

        $product_reviews_sum = Reviews::where('product_id',$reviews->product_id)->where('status','accept')->sum('value');
        $product_reviews_count = Reviews::where('product_id',$reviews->product_id)->where('status','accept')->select('id')->count();

        if($product_reviews_count > 0) {
            $calc = ( round($product_reviews_sum/$product_reviews_count) );
        } else {
            $calc = 0;
        }

        $product->update([
            'reviews' => $calc
        ]);

        /////////////////////////////////////////////////////

        Notifications::create([
            'add_by' => 'admin',
            'user_id' => 1,
            'send_to_type' => 'user',
            'send_to_id' => $reviews->user_id,
            'en_description' => 'we are sorry to tell you about refusing your product review ( '.$product->en_title.' )  ',
            'ar_description' => 'نحن ناسف بأن نخبرك بانة تم رفض تقيمك للمنتج  ( '.$product->ar_title.' )  ',
            'url' => null,
            'seen' => 0,
            'type' => 'order',
            'item_id' => $reviews->id,
            'en_title' => 'refuse product review',
            'ar_title' => ' رفض تقيم المنتج ',
        ]);

        $user = User::where('id',$reviews->user_id)->first();

        if($user != null && $user->device_token != null) {

            $users_device_token = [$user->device_token];
            $title = 'رفض تقيم المنتج';
            $body = 'نحن ناسف بأن نخبرك بانة تم رفض تقيمك للمنتج  ( '.$product->ar_title.' )  ';

            Push_Notification($users_device_token,$title,$body);
        }

        if($lang == 'en') {
            return redirect()->back()->with('error','this reviews refused and deleted successfully');
        } else {
            return redirect()->back()->with('error','تم رفض وحذف هذا التقيم بنجاح');
        }


    }





}
