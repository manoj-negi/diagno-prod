<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medication;
use App\Models\Symptom;
use File;

class MedicationController extends Controller
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
        $q = Medication::select('*')->orderBy('id','DESC');

        if(isset($_GET['paginate'])){
            $records = $request->paginate;
        }

        if (isset($request->filter)) {

            $q->where('status', $request->filter);
        }
        if ($request->search) {

            $q->where(function ($query) use ($search) {

                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%')
                     ->orWhere('dosage', 'LIKE', '%' . $search . '%')
                    ->orWhere('side_effects', 'LIKE', '%' . $search . '%');
            });

        }
        
        $data['data'] = $q->paginate($records)->withQueryString();
        $data['title']= "Medicines";
        $data['page_name']= "List"; 
        return view('admin.medicines.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title']= "Medicines";
        $data['page_name']= "Create";   
        return view('admin.medicines.create',$data);
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
            'name' => 'required|unique:medications,name,'. $request->id,
          
          
        ]);

      try  {
        $data = Medication::UpdateOrCreate([
              'id'=> $request->id,  
        ],[
            'name'                 =>$request->name,
            'spenish_name'         =>$request->spenish_name,
            'description'          =>$request->description,
            'spenish_description'  =>$request->spenish_description,
            'status'               =>$request->status,
            'dosage'               =>$request->dosage,
            'spenish_dosage'       =>$request->spenish_dosage,
            'side_effects'         =>$request->side_effects,
            'spenish_side_effects' =>$request->spenish_side_effects,
            'precautions'          =>$request->precautions,
            'spenish_precautions' =>$request->spenish_precautions,
        ]);  
       
    }
    catch(\Exception $e) {
               
        return  $e->getMessage();
     } 
     if($data)
     {
         if($request->id)
         {
             return redirect()->route('medicines.index')->with('msg','Medicine is successfully updated'); 
         }
         else
         {
             return redirect()->route('medicines.index')->with('msg','Medicine is successfully created'); 
         } 
     }else
     {
         return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
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
        $result['medicine'] = Medication::findOrFail($id);
        $result['title']= "Medicines";
        $result['page_name']= "Edit";           
        if($result['medicine'])
        {
            return view('admin.medicines.create',$result);
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
        $data = Medication::find($id);
        $data->delete();

        return redirect()->route('medicines.index');

    }
}
