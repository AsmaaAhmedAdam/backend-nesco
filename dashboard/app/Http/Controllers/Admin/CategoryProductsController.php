<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Categories as modelRequest;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Categories as Model;
use Intervention\Image\ImageManagerStatic as Image;

class CategoryProductsController extends Controller
{

    private $view = 'admin.categories.products.';
    private $redirect = 'admin_panel/product_categories';


    public function get_lang()
    {
        $lang = session()->get('admin_lang');

        if($lang == 'en' && $lang != null) {
            return $lang;
        } else {
            return 'ar';
        }
    }



    public function popularity($id) {

        $Item = Model::findOrFail($id);

        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }

        if($Item->popularity == 1) {

            $Item->update([ 'popularity' => 0 ]);

            if($lang == 'ar') {
                return redirect()->back()->with('info','تم حذف القسم من الاقسام الشعبية');
            } else {
                return redirect()->back()->with('info','category is removed from popularity categories');
            }
        }

        if($Item->popularity == 0) {

            $Item->update([ 'popularity' => 1 ]);

            if($lang == 'ar') {
                return redirect()->back()->with('success','تم اضافة القسم الي الاقسام الشعبية');
            } else {
                return redirect()->back()->with('success','category is added to popularity categories');
            }
        }
    }



    public function index()
    {
        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }

        $Item = Model::where('type', Model::TYPE['products'])->get([$lang.'_title','id','popularity','pic']);
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
        Model::create($this->gteInput($request,null));
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

        Product::where('category_id',$id)->delete();

        $Item->delete();

        return redirect($this->redirect)->with('error',trans('home.delete_msg'));
    }


    private function gteInput($request,$modelClass) {

        $input = $request->only([
            'en_title' , 'ar_title'
        ]);

        $en_url = $this->Process_Name($request->en_title);
        $ar_url = $this->Process_Name($request->ar_title);

        $custom_name = $en_url;

        $path = public_path('images');

        $input['en_url'] = $en_url;
        $input['ar_url'] = $ar_url;
        $input['type']   = Model::TYPE['products'];
        if(isset($modelClass)) {

            $input['popularity'] = $modelClass->popularity;

            if($request->pic != null) {
                $pic_path = $path.'/'.$custom_name. '.' . request()->file('pic')->extension();
                $img = request()->file('pic')->getRealPath();
                Image::make($img)->widen(200)->save($pic_path, 100);
                $input['pic'] = $custom_name. '.' . request()->file('pic')->extension();
            } 

        } else {

            $input['popularity'] = 0;

            $pic_path = $path.'/'.$custom_name. '.' . request()->file('pic')->extension();
            $img = request()->file('pic')->getRealPath();
            Image::make($img)->widen(200)->save($pic_path, 100);
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
