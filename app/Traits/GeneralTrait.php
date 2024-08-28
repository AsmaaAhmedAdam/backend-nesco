<?php

namespace App\Traits;
use Intervention\Image\ImageManagerStatic as Image;
trait GeneralTrait
{

    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    public function returnError($errorNumber, $message)
    {
        return response()->json([
            'status' => false,
            'errorNumber' => $errorNumber,
            'message' => $message
        ]);
    }

    public function returnResponseWithLink($status,$errorNumber, $message,$link)
    {
        return response()->json([
            'status' => $status,
            'errorNumber' => $errorNumber,
            'message' => $message,
            'link' => $link
        ]);
    }


    public function returnSuccessMessage($message = "", $errorNumber = "200")
    {
        return [
            'status' => true,
            'errorNumber' => $errorNumber,
            'message' => $message
        ];
    }

    public function returnData($key, $value, $message = "")
    {
        return response()->json([
            'status' => true,
            'errorNumber' => "200",
            'message' => $message,
            $key => $value
        ]);
    }

    public function returnList($array=[])
    {
        return response()->json($array);
    }


    //////////////////
    public function returnValidationError($code, $validator)
    {
        return $this->returnError($code, $validator->errors()->first());
    }


    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    public function getErrorCode($input)
    {
        return "E700";
    }


    // 200 success
    // 401 Unauthorized User

    // 403 Unauthenticated user //
    // 404 not found


    // E100 error with message  //
    // E200 catch error //
    // E300 required error //
    // E400 hold data //

    // E600 payment faild  //
    // E700 validation error //

    // E3001 INVALID_TOKEN //
    // E3002 EXPIRED_TOKEN //
    // E3003 TOKEN_NOT_FOUND //


    protected function gteInput($request) 
    {
        $input = $request->only([
            'en_title' , 'ar_title', 'en_description', 'ar_description'
        ]);
        $en_url = $this->Process_Name($request->en_title);
        $ar_url = $this->Process_Name($request->ar_title);
        $custom_name = $en_url;
        $path = public_path('images');
        if($request->pic != null) {
            $pic_path = $path.'/'.$custom_name. '.' . request()->file('pic')->extension();
            $img = request()->file('pic')->getRealPath();
            Image::make($img)->save($pic_path);
            $input['pic'] = $custom_name. '.' . request()->file('pic')->extension();
        } 
        return $input;
    }

    
    protected function getPageInput($request, $object) 
    {
        $en_title = [];
        if(!empty($request->first_en_title)) {
            $en_title['1'] = $request->first_en_title;
        }
        if(!empty($request->second_en_title)) {
            $en_title['2'] = $request->second_en_title;
        }
        if(!empty($request->third_en_title)) {
            $en_title['3'] = $request->third_en_title;
        }
        $input['en_title'] = !empty($en_title) ? $en_title : null;
        if(!empty($object->en_title)) {
            $en_title = json_decode($object->en_title);
            if(!empty($en_title->{1}) && empty($input['en_title']['1'])) {
                $input['en_title']['1'] = $en_title->{1};
            }
            if(!empty($en_title->{2}) && empty($input['en_title']['2'])) {
                $input['en_title']['2'] = $en_title->{2};
            }
            if(!empty($en_title->{3}) && empty($input['en_title']['3'])) {
                $input['en_title']['3'] = $en_title->{3};
            }
        }
        $input['en_title'] = json_encode($input['en_title']);
        $ar_title = [];
        if(!empty($request->first_ar_title)) {
            $ar_title['1'] = $request->first_ar_title;
        }
        if(!empty($request->second_ar_title)) {
            $ar_title['2'] = $request->second_ar_title;
        }
        if(!empty($request->third_ar_title)) {
            $ar_title['3'] = $request->third_ar_title;
        }
        $input['ar_title'] = !empty($ar_title) ? $ar_title : null;
        if(!empty($object->ar_title)) {
            $ar_title = json_decode($object->ar_title);
            if(!empty($ar_title->{1}) && empty($input['ar_title']['1'])) {
                $input['ar_title']['1'] = $ar_title->{1};
            }
            if(!empty($ar_title->{2}) && empty($input['ar_title']['2'])) {
                $input['ar_title']['2'] = $ar_title->{2};
            }
            if(!empty($ar_title->{3}) && empty($input['ar_title']['3'])) {
                $input['ar_title']['3'] = $ar_title->{3};
            }
        }
        $input['ar_title'] = json_encode($input['ar_title']);
        $en_url = $this->Process_Name($object->en_title);
        $custom_name = $en_url;
        $path = public_path('images');
        $pics = [];
        if($request->first_pic != null) {
            $pic_path = $path.'/'.$custom_name.'_first'.'.' . request()->file('first_pic')->extension();
            $img = request()->file('first_pic')->getRealPath();
            Image::make($img)->save($pic_path);
            $pics['1'] = $custom_name.'_first'.'.' . request()->file('first_pic')->extension();
        } 
        if($request->second_pic != null) {
            $pic_path = $path.'/'.$custom_name.'_second'.'.' . request()->file('second_pic')->extension();
            $img = request()->file('second_pic')->getRealPath();
            Image::make($img)->save($pic_path);
            $pics['2'] = $custom_name.'_second'.'.' . request()->file('second_pic')->extension();
        } 
        if($request->third_pic != null) {
            $pic_path = $path.'/'.$custom_name.'_third'.'.' . request()->file('third_pic')->extension();
            $img = request()->file('third_pic')->getRealPath();
            Image::make($img)->save($pic_path);
            $pics['3'] = $custom_name.'_third'.'.' . request()->file('third_pic')->extension();
        } 
        $input['pic'] = !empty($pics) ? $pics : null;
        if(!empty($object->pic)) {
            $pics = json_decode($object->pic);
            if(!empty($pics->{1}) && empty($input['pic']['1'])) {
                $input['pic']['1'] = $pics->{1};
            }
            if(!empty($pics->{2}) && empty($input['pic']['2'])) {
                $input['pic']['2'] = $pics->{2};
            }
            if(!empty($pics->{3}) && empty($input['pic']['3'])) {
                $input['pic']['3'] = $pics->{3};
            }
        }
        $input['pic'] = json_encode($input['pic']);
        $en_description = [];
        if(!empty($request->first_en_description)) {
            $en_description['1'] = $request->first_en_description;
        }
        if(!empty($request->second_en_description)) {
            $en_description['2'] = $request->second_en_description;
        }
        if(!empty($request->third_en_description)) {
            $en_description['3'] = $request->third_en_description;
        }
        $input['en_description'] = !empty($en_description) ? $en_description : null;
        if(!empty($object->en_description)) {
            $en_description = json_decode($object->en_description);
            if(!empty($en_description->{1}) && empty($input['en_description']['1'])) {
                $input['en_description']['1'] = $en_description->{1};
            }
            if(!empty($en_description->{2}) && empty($input['en_description']['2'])) {
                $input['en_description']['2'] = $en_description->{2};
            }
            if(!empty($en_description->{3}) && empty($input['en_description']['3'])) {
                $input['en_description']['3'] = $en_description->{3};
            }
        }
        $input['en_description'] = json_encode($input['en_description']);
        $ar_description = [];
        if(!empty($request->first_ar_description)) {
            $ar_description['1'] = $request->first_ar_description;
        }
        if(!empty($request->second_ar_description)) {
            $ar_description['2'] = $request->second_ar_description;
        }
        if(!empty($request->third_ar_description)) {
            $ar_description['3'] = $request->third_ar_description;
        }
        $input['ar_description'] = !empty($ar_description) ? $ar_description : null;
        if(!empty($object->ar_description)) {
            $ar_description = json_decode($object->ar_description);
            if(!empty($ar_description->{1}) && empty($input['ar_description']['1'])) {
                $input['ar_description']['1'] = $ar_description->{1};
            }
            if(!empty($ar_description->{2}) && empty($input['ar_description']['2'])) {
                $input['ar_description']['2'] = $ar_description->{2};
            }
            if(!empty($ar_description->{3}) && empty($input['ar_description']['3'])) {
                $input['ar_description']['3'] = $ar_description->{3};
            }
        }
        $input['ar_description'] = json_encode($input['ar_description']);
        return $input;
    }


    private function Process_Name($name) {

        $space = array(' ');
        $dash  = array("-");

        $value     = preg_replace('/[^\x{0600}-\x{06FF}a-zA-Z0-9]/u', ' ', $name);
        $url1  = str_replace($space, $dash, $value);
        $url2 = preg_replace('#-+#','-',$url1);

        if($this->isArabic($url2) == false) {
            $url2  = strtolower($url2);
        }

        $first_ch = $url2[0];
        $last_ch = substr($url2, -1);

        if($first_ch == '-') {
            $url2 = substr_replace($url2, "", 0, 1);
        }

        if($last_ch == '-') {
            $url2 = substr_replace($url2, "", strlen($url2)-1, strlen($url2));
        }

        return $url2;
    }


    private function isArabic($string) {

        if(preg_match('/\p{Arabic}/u', $string)) {
            return true;
        } else {
            return false;
        }
    }
}
