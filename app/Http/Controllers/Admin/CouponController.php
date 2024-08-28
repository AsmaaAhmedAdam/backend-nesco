<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Coupon as Model;
use App\Models\Invoice;

class CouponController extends Controller
{

    private $view = 'admin.coupon.';
    private $redirect = 'admin_panel/coupon';


    public function get_lang()
    {
        $lang = session()->get('admin_lang');

        if($lang == 'en' && $lang != null) {
            return $lang;
        } else {
            return 'ar';
        }
    }


    public function coupon_orders($coupon_id) {
        $Item = Invoice::where('coupon_id',$coupon_id)->get();
        return view('admin.invoices.index',compact('Item'));
    }

    public function coupon_accept($id) {

        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }

        $coupon = Model::findOrFail($id);

        $coupon->update([ 'status' => '1' ]);

        if($lang == 'en') {
            return redirect()->back()->with('success','this coupon activated successfuly');
        } else {
            return redirect()->back()->with('success','تم تفعيل قسيمة الخصم بنجاح');
        }

    }



    public function coupon_refused($id) {

        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }

        $coupon = Model::findOrFail($id);

        $coupon->update(['status' => '0']);

        if($lang == 'en') {
            return redirect()->back()->with('error','this coupon disabled successfully');
        } else {
            return redirect()->back()->with('error','تم الغاء تفعيل قسيمة الخصم بنجاح');
        }

    }


    public function index()
    {
        $Item = Model::get();
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
    public function store(Request $request)
    {
        $arr = [
            'title' => 'required|unique:coupon',
            'value_type' => 'required',
        ];

        if($request->value_type == 'value') {
            $arr['value'] = 'required|numeric|min:1';
        }

        if($request->value_type == 'percentage') {
            $arr['value'] = 'required|numeric|min:1|max:100';
        }

        if($request->date_type != null && $request->date_type == 1) {
            $arr['date'] = 'required|date';
        }

        $request->validate($arr);

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
    public function update(Request $request, $id)
    {

        $arr = [
            'title' => 'required|unique:coupon,title,' . $id,
            'value_type' => 'required',
        ];

        if($request->value_type == 'value') {
            $arr['value'] = 'required|numeric|min:1';
        }

        if($request->value_type == 'percentage') {
            $arr['value'] = 'required|numeric|min:1|max:100';
        }

        if($request->date_type != null && $request->date_type == 1) {
            $arr['date'] = 'required|date';
        }

        $request->validate($arr);

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
        $Item->update(['status' => '0']);
        return redirect($this->redirect)->with('error',trans('home.delete_msg'));
    }


    private function gteInput($request,$modelClass) {

        $input = $request->only([
            'title', 'value_type', 'value', 'date'
        ]);

        if(! isset($modelClass)) {
            $input['status'] = 1;
        } else {
            $input['status'] = $modelClass->status;
        }

        if($request->date_type != null) {
            $input['date_type'] = 1;
            $input['date'] = $request->date;
        } else {
            $input['date_type'] = 0;
            $input['date'] = null;
        }

        return  $input;
    }




}
