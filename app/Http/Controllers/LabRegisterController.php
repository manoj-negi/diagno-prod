<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use App\Models\Role;

use App\Models\User;
use App\Models\State;
use App\Models\City;
use App\Models\LabCities;
use Auth;

use Illuminate\Support\Facades\Hash;

class LabRegisterController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
    
        $q = Auth::user();
        // $q = User::where('id',Auth::id())->first();
        $data['hospital_category'] = User::all();
        $data['title'] = "Lab";
        $data['page_name'] = "List";
        $data['updates'] = $q->orderBy('id', 'desc')
                            ->paginate(isset($request->pagination) && !empty($request->pagination) ? $request->pagination : 10)->withQueryString();
                            $data['user'] = LabCities::findOrFail($id);

        return view('admin.lab-register.index', $data);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getcity(Request $request)
    {
        $companyId = $request->state_id; 
        $cite = City::where('state_id', $companyId)->get();
       //return  $cite ;
        $outputDataemployees = '';
        if ($cite->count() > 0) {
            foreach ($cite as $item) {
                $outputDataemployees .= "<option value='{$item->id}'>{$item->city}</option>";
            }
        }
        return response()->json(['status' => true, 'outputDataemployees' => $outputDataemployees,]);
    }


//     if(Auth::user()->roles->contains(1)){
//         $q = User::where('id',$request->id)->first();
//    }
//    return $q;

    public function create(Request $request)
    {
       
      
        $result['title'] = "Profile";
        $result['page_name'] = "Lab Profile";
        if(Auth::user()->roles->contains(1)){
            $result['updates'] = User::where('id',$request->id)->first();
            $result['state'] = State::get();
            $result['city'] = LabCities::where('lab_id',$request->id)->pluck('city')->toArray();
            $user = User::findOrFail($request->id);
            $result['allCity'] = City::where('state_id',$user->state_id)->get();
        }elseif(Auth::user()->roles->contains(4)){
            $result['updates'] = Auth::user();
            $result['state'] = State::get();
            $result['city'] = LabCities::where('lab_id',Auth::id())->pluck('city')->toArray();
            $result['allCity'] = City::where('state_id',Auth::user()->state_id)->get();
        }
        // $result['updates'] = Auth::user()->roles->contains(1);
        // $result['state'] = State::get();
        // $result['city'] = LabCities::where('lab_id',Auth::id())->pluck('city')->toArray();
        // $result['allCity'] = City::where('state_id',Auth::user()->state_id)->get();

        // return $result['new'];
      
        return view('admin.lab-register.create', $result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
     {
        //  $request->validate([
        //     'project_name' => 'required',
        //     'start_date' => 'required|date_format:Y-m-d|before_or_equal:end_date',
        //     'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        //     'type_of_project'=>'required',
        //     'company_id'=>'required',
        //     'budget'=>'required',
        //     'location'=>'required',
        // ]);
        $path = 'uploads/lab-register';
         $documentFile = !empty($request->hospital_logo)
             ? $this->uploadDocuments($request->hospital_logo, $path)
             : $request->old_hospital_logo;
     
             $user = Auth::user();
             $result = User::updateOrCreate(
                 ['id' => $user->id],
                 [
                     'name' => $request->name,
                     'email' => $request->email,
                     'number' => $request->number,
                     'city_id' => $request->city_id,
                     'hospital_logo' => $documentFile,
                     'address' => $request->address,
                     'state_id' => $request->state_id,
                     'home_collection' => $request->home_collection,
                     'postal_code' => $request->postal_code,
                     'gst' => $request->gst,
                 ]
             );
            if(Auth::user()->roles->contains(1)){
                $user = Auth::user();
            $result = User::updateOrCreate(
                ['id' => $user->id],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'number' => $request->number,
                    'city_id' => $request->city_id,
                    'hospital_logo' => $documentFile,
                    'address' => $request->address,
                    'state_id' => $request->state_id,
                    'home_collection' => $request->home_collection,
                    'postal_code' => $request->postal_code,
                    'gst' => $request->gst,
                ]
            );
            }
            // $result = LabCities::updateOrCreate(
            //     ['id' => $user->id],
            //     [
            //         'lab_id' => $result->id,
            //         'city' => $request->city,
                   
            //     ]
            // );
            if (!empty($request->city)) {
                LabCities::all();
              
                foreach ($request->city as $city) {
                    LabCities::updateOrCreate([
                        'lab_id' => $result->id,
                        'city' => $city,
                    ]);
                    
                }
            }
        // }

         if ($result) {
             if ($request->id) {
                 return redirect()->back()->with('msg', 'Lab Register Is Successfully Updated');
             } else {
                 return redirect()->back()->with('msg', 'Lab Register Is Successfully Created');
             }
         } else {
             return redirect()->back()->with('error', 'Something went wrong. Please try again!');
         }
     }
     

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
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
        $data['title'] = "Edit Lab";
        $data['page_name'] = "Edit";
      
         $data['result'] = User::findOrFail($id);
         $data['user'] = LabCities::findOrFail($id);
       
    return view('admin.lab-register.create',$data);   
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
        $data = User::find($id);
        $data->delete();
        return redirect()->route('lab-register.index');
    }

   
}
