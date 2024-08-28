<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Product as modelRequest;
use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use App\Models\Favorite;
use App\Models\Product as Model;
use App\Models\Product_Selling;
use Intervention\Image\ImageManagerStatic as Image;


class ProductsController extends Controller
{

    private $view = 'admin.products.';
    private $redirect = 'admin_panel/products';


    public function get_lang()
    {
        $lang = session()->get('admin_lang');

        if($lang == 'en' && $lang != null) {
            return $lang;
        } else {
            return 'ar';
        }
    }



    // popularity
    public function popularity($id) {

        $Item = Model::findOrFail($id);

        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }


        if($Item->popularity == 1) {

            $Item->update([ 'popularity' => 0 ]);

            if($lang == 'en') {
                return redirect()->back()->with('info','product is removed from popularity products');
            } else {
                return redirect()->back()->with('info','تم حذف المنتج من المنتجات الشعبية');
            }
        }


        if($Item->popularity == 0) {

            $Item->update([ 'popularity' => 1 ]);

            if($lang == 'en') {
                return redirect()->back()->with('success','product is added to popularity products');
            } else {
                return redirect()->back()->with('success','تم اضافة المنتج الي المنتجات الشعبية');
            }
        }

    }






    public function index()
    {
        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }

        $Item = Model::get([$lang.'_title','category_id','id','pic','popularity']);
        return view($this->view . 'index',compact('Item'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view($this->view . 'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(modelRequest $request)
    {

        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }
    
        $Item = Model::create($this->gteInput($request,null));

        return redirect($this->redirect)->with('success',trans('home.save_msg'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Item = Model::findOrFail($id);
        return view($this->view . 'edit', [ 'Item' => $Item ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(modelRequest $request, $id)
    {

        $Item = Model::findOrFail($id);

        $Item->update($this->gteInput($request,$Item));

        return redirect()->back()->with('info',trans('home.update_msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Item = Model::findOrFail($id);

        //// product
        $arr2 = explode('images',$Item->pic);
        if(! empty($arr2) && count($arr2) == 2) {
            $path2 = 'images'.$arr2[1];
            if($Item->pic != null && file_exists($path2)) {
                unlink($path2);
            }
        }
        CartProduct::where('product_id', $id)->delete();
        Favorite::where('product_id', $id)->delete();
        Product_Selling::where('product_id',$id)->delete();

        $Item->delete();

        return redirect($this->redirect)->with('error',trans('home.delete_msg'));
    }



    private function gteInput($request,$modelClass) {
        $input = $request->only([
            'en_title' , 'ar_title' , 'category_id'  ,
            'en_description' , 'ar_description' ,
            'price_before_discount' , 'discount','stock'
        ]);

        if($request->discount == 0) {
            $input['price'] = $request->price_before_discount;
        } else {
            $input['price'] = round(($request->price_before_discount) - (($request->price_before_discount * $request->discount) / 100));
        }

        $en_url = $this->Process_Name($request->en_title);
        $ar_url = $this->Process_Name($request->ar_title);

        $custom_name = $en_url;

        $path = public_path('images');

        $input['en_url'] = $en_url;
        $input['ar_url'] = $ar_url;

        if(isset($modelClass)) {

            $input['popularity'] = $modelClass->popularity;
            $input['reviews'] = $modelClass->reviews;

            if($request->pic != null) {
                $pic_path = $path.'/'.$custom_name. '.' . request()->file('pic')->extension();
                $img = request()->file('pic')->getRealPath();
                Image::make($img)->widen(700)->save($pic_path, 100);
                $input['pic'] = $custom_name. '.' . request()->file('pic')->extension();
            }

        } else {

            $input['popularity'] = 0;
            $input['reviews'] = 0;

            $pic_path = $path.'/'.$custom_name. '.' . request()->file('pic')->extension();
            $img = request()->file('pic')->getRealPath();
            Image::make($img)->widen(700)->save($pic_path, 100);
            $input['pic'] = $custom_name. '.' . request()->file('pic')->extension();

        }


        return  $input;
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
