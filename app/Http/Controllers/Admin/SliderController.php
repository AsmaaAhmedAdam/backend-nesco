<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Slider as Model;
use Intervention\Image\ImageManagerStatic as Image;


class SliderController extends Controller
{

    private $view = 'admin.slider.';
    private $redirect = 'admin_panel/slider';


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $request->validate([
            'pic' => 'required|image|mimes:jpg,png,jpeg',
        ]);

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
        $request->validate([
            'pic' => 'nullable|image|mimes:jpg,png,jpeg',
        ]);

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

        $path = Upload_Path();

        if(isset($modelClass)) {

            if ($request->pic != null) {

                $ext = request()->file('pic')->extension();
                $custom_name =  $modelClass->id.'-slider.' .$ext;

                $pic_path = $path.'/'.$custom_name;
                $img = request()->file('pic')->getRealPath();
                Image::make($img)->widen(1992)->save($pic_path, 100);

                $input['pic'] = $custom_name;

            }

        } else {

            $counter = Model::max('id')+1;
            $ext = request()->file('pic')->extension();
            $custom_name =  $counter.'-slider.' .$ext;

            $pic_path = $path.'/'.$custom_name;
            $img = request()->file('pic')->getRealPath();
            Image::make($img)->widen(1992)->save($pic_path, 100);

            $input['pic'] = $custom_name;

        }



        return  $input;
    }




}
