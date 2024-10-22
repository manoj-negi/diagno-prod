<?php
namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Validate;

use Illuminate\Support\Facades\Auth;

class SliderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $data['title'] = 'Sliders';
        $data['page_name'] = "List";
    
        $q = Slider::query();
    
        if (isset($request->search)) {
            $q->where('title', 'like', '%'.$request->search.'%');
        }
    
        $paginateData = [10, 30, 50,70,100];
    
        $perPage = $request->input('pagination', 10);
    
        $data['data'] = $q->paginate($perPage)->withQueryString();
        $data['paginateData'] = $paginateData;
    
        return view('admin.sliders.index', $data);
    }

    
    public function create()
    {
        $data['page_name'] = "Create";
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
            'image'        => !empty($request->image) ? $this->uploadDocuments($request->image,$path) : 
            $request->old_image,
          
        ]);

        $msg=isset($request->id) && !empty($request->id)?'Updated Successfully.':'Created Successsfully';

        return redirect()->route('sliders.index')->with('data_created',$msg);
                       
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
        $categories['page_name'] = "Edit";
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
        return redirect()->route('sliders.index')->with('msg', 'Slider Deleted Successfully');
    }
}
