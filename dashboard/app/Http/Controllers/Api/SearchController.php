<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\APiResource\Product_R;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Models\Product;
use App\Models\User;
use Exception;


class SearchController extends Controller
{

    use GeneralTrait;

    public $user;
    public $lang;


    public function __construct()
    {
        // $user = JWTAuth::parseToken()->authenticate();
        $auth_user = Auth::guard('user-api')->user();

        if( $auth_user != null) {
            $this->user = User::where('id',$auth_user->id)->first();
        } else {
            $this->user = null;
        }

        // if(getallheaders() != null && ! empty(getallheaders()) && array_key_exists('language',getallheaders())) {
        //     $this->lang = getallheaders()['language'];
        // } else {
        //     $this->lang = null;
        // }

    }



    // search
    public function search(Request $request) {

        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        try {

            $validated_arr = [
                'key' => 'required'
            ];

            $custom_messages = [
                'key.required' => ' المحتوي المراد البحث عنه مطلوب ',
            ];

            if($lang == 'en') {
                $validator = Validator::make($request->all(), $validated_arr);
            } else {
                $validator = Validator::make($request->all(), $validated_arr,$custom_messages);
            }

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }

            $key = $request->key;

            $products_arr1 = Product::where($lang.'_title', 'LIKE', '%' . $key . '%')->pluck('id')->toArray();

            $products_arr2 = Product::where($lang.'_description', 'LIKE', '%' . $key . '%')->pluck('id')->toArray();

            $products_arr = array_merge($products_arr1,$products_arr2);

            if(! empty($products_arr)) {

                $products_arr = array_unique($products_arr);

                $data = Product::whereIn('id',$products_arr)->get(['id',$lang.'_title','price_before_discount','price','size_id','pic']);

                if($data != null && $data->count() > 0) {
                    $data = Product_R::collection($data);
                }

                return $this->returnData('data',$data,'');

            } else {

                if($lang == 'en') {
                    return $this->returnError('E100','Your search for ' . $key . ' did not return any results.');
                } else {
                    return $this->returnError('E100','عفوا لم يتم تطابق اي بيانات مع نتيجة بحثك.');
                }

            }

        } catch(Exception $e) {
            // $e->getLine()
            // $e->getMessage()
            // return $this->returnError('E200',$e->getMessage());
            if($lang == 'en') {
                return $this->returnError('E200','sorry try again');
            } else {
                return $this->returnError('E200','عذرا حاول مرة أخرى');
            }
        }

    }






}
