<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Slider;

class SlidersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $data['title'] = 'Sliders';
        $q = Slider::query();
        if(isset($request->search)) {
            $q->where('title', 'like', '%'.$request->search.'%')->orWhere('url', 'like', '%'.$request->search.'%');
        }
        $data['data'] = $q->paginate(10)->withQueryString();
         
        return view('admin.sliders.index',$data);
    }

    
    public function create()
    {
        $data['title'] = 'Add Slider';
        return view('admin.sliders.create',$data);
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
            'image'       => 'mimes:jpg,jpeg,png',
        ]);

        $path = 'uploads/sliders';
        Slider::updateOrCreate([
            'id'  => $request->id,
        ],[
            'title'        => $request->title,
            'url'          => $request->url,
            'image'        => !empty($request->image) ? $this->UpdateImage($request->image,$path) : 
            $request->old_image,
            'status'       => $request->status,
        ]);

        $msg=isset($request->id) && !empty($request->id)?'Updated Successfully.':'Created Successsfully';

        return redirect()->route('dashboard.sliders.index')->with('data_created',$msg);
                       
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
        $categories['title'] = 'Edit Slider';

        $categories['data'] = Slider::find($id);
        return view('admin.sliders.create',$categories);
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
       //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Slider::find($id)->delete();
        return redirect()->route('dashboard.sliders.index')->with('msg', 'Slider Deleted Successfully');
    }
}
