<?php

namespace App\Http\Controllers\Admin;

use App\Models\Orders;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\User as Model;
use App\Models\Invoice_Details;
use App\Exports\UsersDataExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Users as modelRequest;

class UsersController extends Controller
{

    private $view = 'admin.users.';
    private $redirect = 'admin_panel/users';


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Item = Model::get(['name', 'email','mobile','id','provider_type']);
        return view($this->view . 'index',compact('Item'));
    }

    public function exportExcel()
     {

        return Excel::download( new UsersDataExport ,'users-data.xlsx' );
       

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
            'name', 'email','mobile'
        ]);

        if(isset($modelClass) ) {

            if($request->password != null) {
                $input['password'] =  bcrypt($request->password);
            } else {
                $input['password'] =  $modelClass->password;
            }

       } else {
            $input['password'] =  bcrypt($request->password);
       }

        return  $input;
    }





}
