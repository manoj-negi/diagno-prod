<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Speciality;
use App\Models\Symptom;
use App\Models\Disease;
use File;

class SpecialityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $search = $request->search;

            $records = getenv('ADMIN_PAGE_LIMIT');
            $q = Speciality::select('*')->orderBy('id', 'DESC');

            if (isset($_GET['paginate'])) {
                $records = $request->paginate;
            }

            if (isset($request->filter)) {

                $q->where('status', $request->filter);
            }
            if ($request->search) {

                $q->where(function ($query) use ($search) {

                    $query->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('description', 'LIKE', '%' . $search . '%');
                });

            }

            $data['data'] = $q->paginate($records)->withQueryString();
            $data['title'] = "Speciality";
            $data['page_name'] = "List";
            return view('admin.specialities.index', $data);
        } catch (\Exception $e) {
            
            return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Speciality";
        $data['page_name'] = "Create";
        $data['disease'] = Disease::where('status',1)->pluck('name')->toArray();
        $data['symptom'] = Symptom::where('status',1)->pluck('name')->toArray();
        return view('admin.specialities.create', $data);
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
            'name' => 'required|unique:specialities,name,' . $request->id,
          
        ]);

        $result = Speciality::UpdateOrCreate([
            'id' => $request->id,

        ], [
                'name'         => $request->name,
                'spenish_name' =>$request->spenish_name,
                'description'  => $request->description,
                'spenish_description'  => $request->spenish_description,
                'status'       => $request->status
            ]);
            $result->diseases()->sync($request->input('disease', []));
            $result->symptoms()->sync($request->input('symptom', []));
            if($result)
            {
                if($request->id)
                {
                    return redirect()->route('specialities.index')->with('msg','specialities is successfully updated'); 
                }
                else
                {
                    return redirect()->route('specialities.index')->with('msg','specialities is successfully created'); 
                } 
            }else
            {
                return redirect()->back()->with('msg', 'Something went Wrong, Please try again!');
            } 

      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['title'] = "Speciality";
        $data['page_name'] = "Edit";
        $data['disease'] = Disease::where('status',1)->pluck('name');
        $data['symptom'] = Symptom::where('status',1)->pluck('name');
        $data['result'] = Speciality::findOrFail($id);
        if($data['result'])
        {
            return view('admin.specialities.create',$data);
        }
        else
        {
            return redirect()->back()->with('success', 'Data not found');   
        }
        
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
        $data = Speciality::find($id);
        $data->delete();
        if($data)
        {
            return redirect()->route('specialities.index')->with('msg','specialities is successfully Deleted'); 
        }
        else
        {
            return redirect()->route('specialities.index')->with('msg','specialities unsuccessfully Deleted'); 
        }
    
    }
}