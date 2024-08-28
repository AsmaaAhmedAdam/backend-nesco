<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    use GeneralTrait;
    private $view = 'admin.pages.';
    private $redirect = 'admin_panel/site_pages';


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
        $images = [];
        $lang = $this->get_lang();
        if($lang == null) {
            $lang = 'ar';
            app()->setLocale('ar');
            session()->put('admin_lang','ar');
        }
        if($lang == null) {
            $lang = 'ar';
            app()->setLocale('ar');
            session()->put('admin_lang','ar');
        }

        
        $Item   = Page::get(['id', 'main_title_'.$lang, 'pic']);
        foreach($Item as $item) {
            $images[$item->id] = !is_null($item->pic) ? json_decode($item->pic) : null;
        }
        return view($this->view . 'index', compact('Item','images'));
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
     * @param  \Illuminate\Http\CreatePageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePageRequest $request)
    {
        Page::create($this->gteInput($request)); 
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
        $Item = Page::findOrFail($id);
        $en_title  = !is_null($Item->en_title) ? json_decode($Item->en_title) : null;
        $ar_title  = !is_null($Item->ar_title) ? json_decode($Item->ar_title) : null;
        $images    = !is_null($Item->pic) ? json_decode($Item->pic) : null;
        $ar_desc   = !is_null($Item->ar_description) ? json_decode($Item->ar_description) : null;
        $en_desc   = !is_null($Item->en_description) ? json_decode($Item->en_description) : null;
        return view($this->view . 'edit', ['ar_title' => $ar_title, 'en_title' => $en_title, 'Item' => $Item, 'images' => $images, 'ar_description' => $ar_desc, 'en_description' => $en_desc]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePageRequest $request, $id)
    {
        $Item = PAge::findOrFail($id);
        $Item->update($this->getPageInput($request, $Item));
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
        //
    }
}
