<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Menu;
use App\Models\Product;
use Intervention\Image\ImageManagerStatic as Image;

class MenuCategoryController extends Controller
{
    private $view = 'admin.categories.menu.';
    private $redirect = 'admin_panel/menu_categories';

    public function get_lang()
    {
        $lang = session()->get('admin_lang');

        if($lang == 'en' && $lang != null) {
            return $lang;
        } else {
            return 'ar';
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }
        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }
        $Item = Categories::where('type', Categories::TYPE['menu'])->get([$lang.'_title','id','popularity','pic']);
        return view($this->view . 'index', compact('Item'));
    }

    // popularity
    public function popularity($id) {
        $Item = Categories::findOrFail($id);

        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }


        if($Item->popularity == 1) {

            $Item->update([ 'popularity' => 0 ]);

            if($lang == 'en') {
                return redirect()->back()->with('info','category is removed from popularity products');
            } else {
                return redirect()->back()->with('info','تم حذف التصنيف من المنتجات الشعبية');
            }
        }


        if($Item->popularity == 0) {

            $Item->update([ 'popularity' => 1 ]);

            if($lang == 'en') {
                return redirect()->back()->with('success','category is added to popularity products');
            } else {
                return redirect()->back()->with('success','تم اضافة التصنيف الي المنتجات الشعبية');
            }
        }

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
    public function store(Request $request)
    {
        Categories::create($this->gteInput($request,null));
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
        $Item = Categories::findOrFail($id);
        return view($this->view . 'edit', [ 'Item' => $Item ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Item = Categories::findOrFail($id);
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
        $Item = Categories::findOrFail($id);

        Menu::where('category_id',$id)->delete();

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
        $input['type']   = Categories::TYPE['menu'];
        if(isset($modelClass)) {

            $input['popularity'] = $modelClass->popularity;

            if($request->pic != null) {
                $pic_path = $path.'/'.$custom_name. '.' . request()->file('pic')->extension();
                $img = request()->file('pic')->getRealPath();
                Image::make($img)->save($pic_path);
                $input['pic'] = $custom_name. '.' . request()->file('pic')->extension();
            }

        } else {

            $input['popularity'] = 0;

            $pic_path = $path.'/'.$custom_name. '.' . request()->file('pic')->extension();
            $img = request()->file('pic')->getRealPath();
            Image::make($img)->save($pic_path);
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
