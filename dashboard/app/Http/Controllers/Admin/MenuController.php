<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Product as modelRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\CartProduct;
use App\Models\Favorite;
use App\Models\Menu;
use App\Models\Product as Model;
use App\Models\Product_Selling;
use Intervention\Image\ImageManagerStatic as Image;


class MenuController extends Controller
{

    private $view = 'admin.menu.';
    private $redirect = 'admin_panel/menu';


    public function get_lang()
    {
        $lang = session()->get('admin_lang');
        if($lang == 'en' && $lang != null) {
            return $lang;
        } else {
            return 'ar';
        }
    }

    public function index()
    {
        $lang = $this->get_lang();
        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }
        $Item = Menu::get([$lang.'_title','category_id','id', 'popularity', 'pic']);
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
     * @param  \Illuminate\Http\CreateMenuRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMenuRequest $request)
    {
        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }

        Menu::create($this->getStoreInput($request));

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
        $Item = Menu::findOrFail($id);
        $nutritionFacts = !empty($Item) ? json_decode($Item->nutrition_facts) : null;
        return view($this->view . 'edit', [ 'Item' => $Item, 'nutritionFacts' => $nutritionFacts ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMenuRequest $request, $id)
    {
        $Item = Menu::findOrFail($id);
        $Item->update($this->getUpdateInput($request,$Item));
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
        $Item = Menu::findOrFail($id);
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

    // popularity
    public function popularity($id) {
        $Item = Menu::findOrFail($id);

        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }


        if($Item->popularity == 1) {

            $Item->update([ 'popularity' => 0 ]);

            if($lang == 'en') {
                return redirect()->back()->with('info','menu product is removed from popularity products');
            } else {
                return redirect()->back()->with('info','تم حذف المشروب من المنتجات الشعبية');
            }
        }


        if($Item->popularity == 0) {

            $Item->update([ 'popularity' => 1 ]);

            if($lang == 'en') {
                return redirect()->back()->with('success','menu product is added to popularity products');
            } else {
                return redirect()->back()->with('success','تم اضافة المشروب الي المنتجات الشعبية');
            }
        }

    }
    private function getUpdateInput($request,$modelClass) {

        $input = $request->only([
            'en_title', 'ar_title', 'category_id', 'en_description', 'ar_description'
        ]);
        if(isset($request->has_nutrition_facts)) {
            $input['has_nutrition_facts'] = (int)$request->has_nutrition_facts;
        }
        $path = public_path('images');
        if($request->pic != null) {
            $custom_name = time();
            $pic_path = $path.'/'.$custom_name. '.' . request()->file('pic')->extension();
            $img = request()->file('pic')->getRealPath();
            Image::make($img)->save($pic_path);
            $input['pic'] = $custom_name. '.' . request()->file('pic')->extension();
        }
        $nutritionFacts = !empty($modelClass->nutrition_facts) ? json_decode($modelClass->nutrition_facts) : null;
        $nutrition_facts = [];
        foreach(Menu::NUTRITION_FACTS as $fact) {
            if($fact == 'allergens_icon' && $request->allergens_icon != null) {
                $custom_name = time();
                $pic_path = $path.'/'.$custom_name. '.' . request()->file('allergens_icon')->extension();
                $img = request()->file('allergens_icon')->getRealPath();
                Image::make($img)->save($pic_path);
                $nutrition_facts[$fact] = $custom_name. '.' . request()->file('allergens_icon')->extension();
                continue;
            }
            $nutrition_facts[$fact] = isset($request->{$fact}) ? (string) $request->{$fact} : (isset($nutritionFacts->{$fact}) ? $nutritionFacts->{$fact} : "null");
        }
        $input['nutrition_facts'] = json_encode($nutrition_facts);
        return $input;
    }

    private function getStoreInput($request)
    {
        $input = $request->only([
            'en_title', 'ar_title', 'category_id', 'en_description', 'ar_description'
        ]);
        if(isset($request->has_nutrition_facts)) {
            $input['has_nutrition_facts'] = (int)$request->has_nutrition_facts;
        }
        $path = public_path('images');
        if($request->pic != null) {
            $custom_name = time();
            $pic_path = $path.'/'.$custom_name. '.' . request()->file('pic')->extension();
            $img = request()->file('pic')->getRealPath();
            Image::make($img)->save($pic_path);
            $input['pic'] = $custom_name. '.' . request()->file('pic')->extension();
        }
        $nutrition_facts = [];
        foreach(Menu::NUTRITION_FACTS as $fact) {
            if($fact == 'allergens_icon' && $request->allergens_icon != null) {
                $custom_name = time();
                $pic_path = $path.'/'.$custom_name. '.' . request()->file('allergens_icon')->extension();
                $img = request()->file('allergens_icon')->getRealPath();
                Image::make($img)->save($pic_path);
                $nutrition_facts[$fact] = $custom_name. '.' . request()->file('allergens_icon')->extension();
                continue;
            }
            if(isset($request->{$fact})) {
                $nutrition_facts[$fact] = (string) $request->{$fact};
            }
        }
        $input['nutrition_facts'] = json_encode($nutrition_facts);
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
