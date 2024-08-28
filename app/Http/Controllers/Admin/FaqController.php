<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Faq as modelRequest;
use App\Http\Controllers\Controller;
use App\Models\Faq as Model;


class FaqController extends Controller
{

    private $view = 'admin.faq.';
    private $redirect = 'admin_panel/faq';



    public function update_status($id,$status) {

        $Item = Model::findOrFail($id);

        if($status == 0) {

            $Item->delete();
            return redirect()->back()->with('error','question is refused and deleted successfully');

        } elseif ($status == 1) {

            $Item->update(['status' => 1]);
            return redirect()->back()->with('success','question is accepted successfully');

        } else {
            return redirect()->back()->with('error','try again');
        }

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Item = Model::get(['en_title','ar_title','id']);
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

        $input = $request->only([
            'en_title','ar_title',
            'en_description','ar_description'
        ]);

        return  $input;
    }




}
