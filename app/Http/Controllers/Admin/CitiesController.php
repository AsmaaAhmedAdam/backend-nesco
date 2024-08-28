<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Cities as modelRequest;
use App\Http\Controllers\Controller;
use App\Models\Cities as Model;



class CitiesController extends Controller
{

    private $view = 'admin.cities.';
    private $redirect = 'admin_panel/cities';


    public function get_lang()
    {
        $lang = session()->get('admin_lang');

        if($lang == 'en' && $lang != null) {
            return $lang;
        } else {
            return 'ar';
        }
    }


    public function un_active($id) {

        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }

        $Item = Model::findOrFail($id);

        $Item->update([ 'status' => 0 ]);

        if($lang == 'en') {
            return redirect()->back()->with('error','The city has been successfully deactivated');
        } else {
            return redirect()->back()->with('error','تم الغاء تفعيل المدينة بنجاح');
        }


    }

    public function active($id) {

        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }

        $Item = Model::findOrFail($id);

        $Item->update([ 'status' => 1 ]);

        if($lang == 'en') {
            return redirect()->back()->with('success','The city has been activated successfully.');
        } else {
            return redirect()->back()->with('success','تم تفعيل المدينة بنجاح');
        }


    }

    public function index()
    {
        $Item = Model::get(['en_name','ar_name','id','status','shipping_value']);
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
        $Item->delete();
        return redirect($this->redirect)->with('error',trans('home.delete_msg'));
    }


    private function gteInput($request,$modelClass) {

        $input = $request->only(['en_name','ar_name','shipping_value']);

        if(! isset($modelClass)) {
            $input['status'] = 1;
        } else {
            $input['status'] = $modelClass->status;
        }

        return  $input;
    }


}
