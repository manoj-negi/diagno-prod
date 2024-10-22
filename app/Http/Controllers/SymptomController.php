<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Symptom;
use validate;
use File;

class SymptomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $records = getenv('ADMIN_PAGE_LIMIT');
        $q = Symptom::select('*')->orderBy('id','DESC');

        if(isset($_GET['paginate'])){
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
        $data['title'] = "Symptoms";
        $data['page_name'] = "List";
        $data['data'] = $q->paginate($records)->withQueryString();

        return view('admin.symptoms.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $result['title'] = "Symptoms";
        $result['page_name'] = "Create";
        return view('admin.symptoms.create',$result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|unique:symptoms,name,'.$request->id,
           

        ]);

    try {
        $data = Symptom::UpdateOrCreate([
              'id' => $request->id,
        ],[
             'name'                => $request->name,
             'spenish_name'        => $request->spenish_name,
             'description'         => $request->description,
             'spenish_description' => $request->spenish_description,
             'status'              => $request->status,
             'affected_area'       => $request->affected_area,
             'spenish_affected_area'=> $request->spenish_affected_area
             
        ]);
        if($data)
        {
            if($request->id)
            {
                return redirect()->route('symptoms.index')->with('msg','Symptom is successfully updated'); 
            }
            else
            {
                return redirect()->route('symptoms.index')->with('msg','Symptom is successfully created'); 
            } 
        }else
        {
            return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
        } 

    }
    catch (\Exception $e) {

       /// echo $e->getMessage();
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
        $result['result'] = Symptom::findOrFail($id);
        $result['title'] = "Symptoms";
        $result['page_name'] = "Edit";
        if($result['result'])
        {
            return view('admin.symptoms.create',$result);
        }
        else
        {
            return redirect()->back()->with('error', 'Data not found');   
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
        $data = Symptom::find($id);
        $data->delete();

        if($data)
        {
            return redirect()->route('symptoms.index')->with('msg','Symptom is successfully Deleted'); 
        }
        else
        {
            return redirect()->route('symptoms.index')->with('error','Symptom successfully Deleted'); 
        }
    }
}