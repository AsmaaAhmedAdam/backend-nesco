<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\APiResource\AllMenuDetailsResource;
use App\Http\Resources\APiResource\AllPagesDetailsResource;
use App\Http\Resources\ApiResource\AllProductDetailsResource;
use App\Http\Resources\ApiResource\AllReviewsResource;
use App\Http\Resources\APiResource\Auth_Product_Details;
use App\Http\Resources\APiResource\MenuDetailsResource;
use App\Http\Resources\APiResource\PageDetailsResource;
use App\Models\Cart;
use App\Models\Categories;
use App\Models\Cities;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Models\Product;
use App\Models\Product_Selling;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{

    use GeneralTrait;

    public $user;
    public $lang;


    public function __construct()
    {
        /*
        // $user = JWTAuth::parseToken()->authenticate();
        $auth_user = Auth::guard('user-api')->user();

        if( $auth_user != null) {
            $this->user = User::where('id',$auth_user->id)->first();
        } else {
            $this->user = null;
        }
        */

        // if(getallheaders() != null && ! empty(getallheaders()) && array_key_exists('language',getallheaders())) {
        //     $this->lang = getallheaders()['language'];
        // } else {
        //     $this->lang = null;
        // }

    }


    // setting
    public function setting() {

        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        // setting
        $setting = Setting::select([
            'email', 'mobile', 'website_name','facebook_link', 'instgram_link',
            'twitter_link',$lang.'_address as address','whatsapp','android_link','ios_link',
            $lang.'_policy as policy'
        ])->first();

        $setting = $setting->toArray();

        $setting['cities'] = Cities::where('status',1)->get(['id',$lang.'_name as name']);

        return $this->returnData('data',$setting,'');
    }

    // faq
    public function faq() {

        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        $data = Faq::get(['id',$lang.'_title as question',$lang.'_description as answer']);
        return $this->returnData('data',$data,'');

    }


    // slider
    public function slider() {

        $data = Slider::get(['id','pic']);
        return $this->returnData('data',$data,'');

    }


    // popular - categories
    public function popular_categories() {

        $lang = app()->getLocale();
        
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        $categories = Categories::where('popularity',1)->take(5)->inRandomOrder()->get([ 'id' , $lang.'_title as title', 'pic as image']);

        if(! empty($categories)) {
            return $this->returnData('data',$categories,'');
        } else {
            if($lang == 'en') {
                return $this->returnError('404','sorry there are no any categories');
            } else {
                return $this->returnError('404','عفوا لا يوجود أقسام');
            }
        }

    }


    // all categories
    public function categories(Request $request, $id = null) {

        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        if($id == null) {
            $data = Categories::when($request->type, function($query) use($request) {
                $query->where('type', $request->type);
            })
            ->get(['id',$lang.'_title as title','type','pic as image']);
            return $this->returnData('data',$data,'');

        }
        $raw = Categories::where('id',$id)->first();
        if($raw != null) {
            $data = Product::where('category_id',$id)->select(['id',$lang.'_title as title',$lang.'_description as description','price_before_discount','discount','price','reviews','pic as image'])->paginate(10);
            if($data != null && $data->count() > 0) {
                return $this->returnData('data',$data,'');
            } else {
                if($lang == 'en') {
                    return $this->returnError('404','sorry there are no any products in this category');
                } else {
                    return $this->returnError('404','عفوا لا يوجود منتجات في هذا القسم');
                }
            }

        } else {
            if($lang == 'en') {
                return $this->returnError('404','sorry this category not found');
            } else {
                return $this->returnError('404','عفوا هذا القسم غير موجود');
            }
        }
    }



    // some best selling
    public function some_best_selling() {

        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        $products_arr = Product_Selling::orderBy('count','desc')->pluck('product_id')->toArray();

        //return $products_arr;

        if(! empty($products_arr)) {

            $products_arr = array_unique($products_arr);

            $products = Product::whereIn('id',$products_arr)->orderByRaw('FIELD(id,'.implode(",",$products_arr).')')->take(5)->get(['id',$lang.'_title as title',$lang.'_description as description','price_before_discount','discount','price','reviews','pic as image']);

            return $this->returnData('data',$products,'');

        } else {
            return $this->returnData('data',null,'');
        }

    }


    // all best selling
    public function all_best_selling() {

        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        $products_arr = Product_Selling::orderBy('count','desc')->pluck('product_id')->toArray();

        if(! empty($products_arr)) {

            $products_arr = array_unique($products_arr);

            $products = Product::whereIn('id',$products_arr)->orderByRaw('FIELD(id,'.implode(",",$products_arr).')')->select(['id',$lang.'_title as title',$lang.'_description as description','price_before_discount','discount','price','reviews','pic as image'])->paginate(10);

            if($products != null && $products->count() > 0) {
                return $this->returnData('data',$products,'');
            } else {
                return $this->returnData('data',null,'');
            }

        } else {
            return $this->returnData('data',null,'');
        }

    }

    // some new arrival products
    public function some_new_arrival_products() {

        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        $products = Product::orderBy('created_at','desc')->take(5)->get(['id',$lang.'_title as title',$lang.'_description as description','price_before_discount','discount','price','reviews','pic as image']);

        return $this->returnData('data',$products,'');

    }


    // all latest products
    public function new_arrival_products() {

        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        $products = Product::orderBy('created_at','desc')->select(['id',$lang.'_title as title',$lang.'_description as description','price_before_discount','discount','price','reviews','pic as image'])->paginate(10);

        if($products != null && $products->count() > 0) {
            return $this->returnData('data',$products,'');
        } else {
            return $this->returnData('data',null,'');
        }

    }

    /**
     * product priview.
     *
     * @param  int  $id
     * @return json
     */
    public function view_product($id) 
    {
        // $user = JWTAuth::parseToken()->authenticate();
        $auth_user = Auth::guard('user-api')->user();
        $this->user = !is_null($auth_user) ? User::where('id',$auth_user->id)->first() : null;
        $lang = app()->getLocale();
        if(empty($lang)) {
            return $this->returnError('E300', trans('api.language_required'));
        }
        $product = Product::where('id',$id)->first();
        if(! $product) {
            return $this->returnError('404',trans('api.product_not_found') );
        } 
        $data = new Auth_Product_Details($product,$this->user->id ?? null);
        return $this->returnData('data',$data,'');
    }

    /**
     * get all products.
     *
     * @param  illuminate\Http\Request $request
     * @return json
    */
    public function all_product(Request $request) 
    {
        $filter_data = $request->all();
        $products = Product::with(['favourite', 'cartProduct', 'category', 'productReviews', 'bestSelling']);
        if(!empty($filter_data['category_id'])) {
            $products = $products->where('category_id', $filter_data['category_id']);
        }
        if(!empty($filter_data['search'])) {
            $search = $filter_data['search'];
            $products = $products
            ->where(function($w) use($search) {
                $w->where('en_title', 'LIKE', '%' . $search . '%');
                $w->orWhere('ar_title', 'LIKE', '%' . $search . '%');
            })
            ->orWhere(function($o) use($search) {
                $o->where('en_description', 'LIKE', '%' . $search . '%');
                $o->orWhere('ar_description', 'LIKE', '%' . $search . '%');
            });
        }
        if(!empty($filter_data['order'])) {
            if($filter_data['order'] == Product::NEW_ARRIVAL_PRODUCT) {
                $products = $products->orderBy('id', 'DESC');
            } elseif($filter_data['order'] == Product::BEST_SELLING_PRODUCT) {
                $products = $products->whereHas('bestSelling', function($q) {
                    $q->orderBy('count', 'DESC');
                });
            }
        }
        $products = $products->paginate(20);
        $authCart = Cart::where('user_id', Auth::guard('user-api')->user()->id ?? null)->select('id')->first();
        $dataMapped = $this->mapProducts($products, $authCart); 
        return $this->returnList([
            'status'      => true,
            'errorNumber' => "200",
            'message'     => trans('api.products_list'),
            'total'       => $products->total(), 
            'per_page'    => 20,
            'data'        => $dataMapped, 
        ]);
    }

    /**
     * mapp all products data.
     *
     * @param collection $products
     * @param object $authCart
     * @return array
    */
    private function mapProducts($products, $authCart)
    {
        $mapping = [];
        $lang = app()->getLocale();
        foreach($products as $product) {
            if(empty($product)) {
                continue;
            }
            $productReviews = $product->productReviews ?? null;
            $check_cart = ($product->cartProduct->isNotEmpty() && !is_null($authCart)) ? $product->cartProduct->where('cart_id', $authCart->id) : null; 
            $data = [
            'id'                    => $product->id,
            'title'                 => $product->{$lang.'_title'},
            'category'              => $product->category->{$lang.'_title'} ?? null,
            'price_before_discount' => $product->price_before_discount,
            'discount'              => $product->discount,
            'price'                 => $product->price,
            'stock'                 => $product->stock,
            'description'           => $product->{$lang.'_description'},
            'image'                 => $product->pic,
            'favorite'              => (!is_null($product->favourite)) ? true : false,
            'having_review'         => ($productReviews->isNotEmpty() && !empty($productReviews->where('user_id', Auth::guard('user-api')->user()->id ?? null))) ? true : false,
            'reviews'               => ($productReviews->isNotEmpty()) ? $productReviews->toArray() : null,
            'rate'                  => ($productReviews->isNotEmpty()) ? $this->getRate($productReviews) : 0,
            'product_in_cart'       => (!is_null($check_cart)) ? true : false,
            ];
            $mapping[] = $data;
        }
        return $mapping;
    }

    /**
     * get all menu data.
     *
     * @param  illuminate\Http\Request $request
     * @return json
    */
   public function menu(Request $request) 
    {
        $menu = Menu::when($request->category_id, function($query) use($request) {
            $query->where('category_id', $request->category_id);
            $query->with('category:id,'.app()->getLocale().'_title');
        })
        ->when($request->search, function($query) use($request) {
            $search = $request->search;
            $query->where(function($w) use($search) {
                $w->where('en_title', 'LIKE', '%' . $search . '%');
                $w->orWhere('ar_title', 'LIKE', '%' . $search . '%');
            })
            ->orWhere(function($o) use($search) {
                $o->where('en_description', 'LIKE', '%' . $search . '%');
                $o->orWhere('ar_description', 'LIKE', '%' . $search . '%');
            });
        });
        $menu = $menu->paginate(30);
        return $this->returnList([
            'status'      => true,
            'errorNumber' => "200",
            'message'     => trans('api.menu_list'),
            'total'       => $menu->total(), 
            'per_page'    => 20,
            'category'    => $menu->first()->category->{app()->getLocale().'_title'} ?? null,
            'data'        => AllMenuDetailsResource::collection($menu), 
        ]);
    }

    public function menuDetails($id)
    {
        $menuItem = Menu::findOrFail($id);
        return $this->returnData('data', new MenuDetailsResource($menuItem), trans('api.menu_details'));
    }

    /**
     * get all pages data.
     *
     * @param  illuminate\Http\Request $request
     * @return json
    */
    
    public function allPages(Request $request) 
    {
        $lang  = app()->getLocale();
        $pages = new Page();
        $pages = $pages->paginate(20);
        return $this->returnList([
            'status'      => true,
            'errorNumber' => "200",
            'message'     => trans('api.pages_list'),
            'total'       => $pages->total(), 
            'per_page'    => 20,
            'data'        => AllPagesDetailsResource::collection($pages), 
        ]);
    }

    public function pageDetails($id)
    {
        $page = Page::findOrFail($id);
        return $this->returnData('data', new PageDetailsResource($page), trans('api.page_details'));
    }
    
    /**
     * Transform the resource into an array.
     *
     * @param  collection $authReviews
     * @return double
     */
    private function getRate($authReviews) : float 
    {
        $sum = 0;
        $count = $authReviews->count();
        foreach($authReviews as $review) {
            if(empty($review)) {
                continue;
            }
            $sum += (float)$review->value;
        }
        return ($count != 0) ? round(($sum / $count), 3) : 0.000;
    }

    /**
     * git active cities.
     *
     * @return json
     */
    public function cities()  
    {
        $cities = Cities::where('status', 1)->select('id', app()->getLocale().'_name', 'shipping_value')->get();
        if($cities->isNotEmpty()) {
            return $this->returnData('data', $cities, trans('api.all_cities'));
        }
        return $this->returnError('E300',trans('api.empty_cities'));
        
        
    }
    
     public function careers(Request $request)
    {
        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }
        
        $data = [
            
            'fullName' =>$request->input('fullName'),
            'email' =>$request->input('email'),
            'dateOfBirth' =>$request->input('dateOfBirth'),
            'gender' =>$request->input('gender'),
            'maritalStatus' =>$request->input('maritalStatus'),
            'nationality' =>$request->input('nationality'),
            'group' =>$request->input('yes'),
            'group' =>$request->input('no'),
            'country' =>$request->input('country'),
            'district' =>$request->input('district'),
            'mobile' =>$request->input('mobile'),
            'school' =>$request->input('school'),
            'schoolqualification' =>$request->input('schoolqualification'),
            'Job' =>$request->input('Job'),
            'upload' =>$request->input('upload')
            
            
            
            
            ];
            
            //$pdf = PDF::loadView('email.careers', $data);

        // Send email to user
         Mail::send('emails.careers', ['data1'=> $data],function($m){
             
             $m->to('asmaa.maher.group@gmail.com')->subject('contact Form Mail!');
             
         });

         if($lang == 'en') {
             return $this->returnSuccessMessage('Your data has been sent successfully!');
         } else {
             return $this->returnSuccessMessage('تم ارسال بياناتك بنجاح');
         }
 

    }
    
         public function franchising(Request $request)
    {
        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }
        
        $data = [
            
            'fullName' =>$request->input('fullName'),
            'IDNumber' =>$request->input('IDNumber'),
            'nationality' =>$request->input('nationality'),
            'dateOfBirth' =>$request->input('dateOfBirth'),
            'educQualification' =>$request->input('educQualification'),
            'mobileNumber' =>$request->input('mobileNumber'),
            'email' =>$request->input('email'),
            'phoneNumber' =>$request->input('phoneNumber'),
            'address' =>$request->input('address'),

            'annualIncome' =>$request->input('annualIncome'),
            'bankAccountType' =>$request->input('bankAccountType'),
            'commercialActivity' =>$request->input('commercialActivity'),
            'expectedCapital' =>$request->input('expectedCapital'),
            'lown' =>$request->input('lown'),
            'manageBusiness' =>$request->input('manageBusiness'),
            'havepartners' =>$request->input('havepartners'),

            'jobTitle' =>$request->input('jobTitle'),
            'employer' =>$request->input('employer'),
            'workAddress' =>$request->input('workAddress'),
            'joiningDate' =>$request->input('joiningDate'),
            'experience' =>$request->input('experience'),
            'companyEmail' =>$request->input('companyEmail'),
            'previousJobs' =>$request->input('previousJobs'),

            'franchiseReason' =>$request->input('franchiseReason'),
            'branchesToStart' =>$request->input('branchesToStart'),
            'expandPlans' =>$request->input('expandPlans'),
            'franchiseCity' =>$request->input('franchiseCity'),
            'suggestedLocation' =>$request->input('suggestedLocation'),
            'suggestions' =>$request->input('suggestions'),
            'uploadFile' =>$request->input('uploadFile'),


            
            ];

         // Send email to user
         Mail::send('emails.franchise', ['data1'=> $data],function($m){
             
             $m->to('asmaa.maher.group@gmail.com')->subject('contact Form Mail!');
             
         });

         if($lang == 'en') {
             return $this->returnSuccessMessage('Your data has been sent successfully!');
         } else {
             return $this->returnSuccessMessage('تم ارسال بياناتك بنجاح');
         }
  }
  
  
  
  public function corporatelogin(Request $request)
  {
      $lang = app()->getLocale();
     
      if(empty($lang)) {
          return $this->returnError('E300','language is required');
      }
      
      $data = [
          
          'fullName' =>$request->input('fullName'),
          'phone' =>$request->input('phone'),
          'branch' =>$request->input('branch'),
          'complaint' =>$request->input('complaint'),
          'upload' =>$request->input('upload')
          
          ];

       // Send email to user
       Mail::send('emails.corporatelogin', ['data1'=> $data],function($m){
           
           $m->to('asmaa.maher.group@gmail.com')->subject('contact Form Mail!');
           
       });

       if($lang == 'en') {
           return $this->returnSuccessMessage('Your data has been sent successfully!');
       } else {
           return $this->returnSuccessMessage('تم ارسال بياناتك بنجاح');
       }
}





    
    
}
