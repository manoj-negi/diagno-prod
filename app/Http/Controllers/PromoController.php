<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Hospital;
use App\Models\HospitalCategory; 
use File;
use Datatables;
use App\Models\Role;
use App\Models\Doctor_hospital; 
use App\Models\User;
use Auth;
use App\Models\State;
use App\Models\hospitalsReport;
use App\Models\City;
use App\Models\LabTest;
use App\Models\Promo;
use App\Models\HospitalDoctor;
use App\Models\PatientReport;
use App\Models\Appointment;
use App\Models\Package;
use App\Models\LabSelectedPackages;
use Illuminate\Support\Facades\Hash;

class PromoController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    $data['title'] = "Promo Code";
    $data['page_name'] = "List";

    $today = Carbon::today();

    // Fetch Promo where Validity is today
    $dataTest = Promo::query();
  
    if(!empty($request->search)){
        $dataTest->where('title', 'LIKE', '%' . $request->search . '%');
    }
    $data['data'] = $dataTest->orderBy('id','DESC')->paginate($request->pagination ?? 10)->withQueryString();

    return view('admin.promo.index', $data);
    }
    public function indexDiagno(Request $request)
    {
    $data['title'] = "Promo";
    $data['page_name'] = "Promo";
    $dataTest = Package::where('type','test')->where('lab_id',1);
  
    if(!empty($request->search)){
        $dataTest->where('package_name', 'LIKE', '%' . $request->search . '%');
    }
    if(!empty($request->lab)){
        $dataTest->where('lab_id',$request->lab);
    }
  
    $data['data'] = $dataTest->orderBy('id','DESC')->paginate($request->pagination ?? 10)->withQueryString();
    return view('admin.promo.index', $data);
    }
    public function labtestUpdate(Request $request, $id)
    {
        $data = Package::find($id);
        $data->update(['admin_status'=> $request->admin_status]);
            return redirect()->back()->with('msg', 'Status Approved');
    }
    
    public function hospitalUpdate(Request $request, $id)
    {
        $data = User::where('id', $id)->first();
        $data->is_approved = $request->is_approved;
        $data->save();
        
        return redirect()->back()->with('msg', 'Status Updated Successfully');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $result['title'] = "Promo Code";
        $result['page_name'] = "Create";
        $result['labs'] = User::whereHas('roles', function ($query) {
            $query->where('id', 4);
        })->get();
        
        return view('admin.promo.create', $result);
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
            'title' => 'required',
            // 'image' => 'required',
            'validity' => 'required|date_format:Y-m-d',
            'validity_end' => 'required|date_format:Y-m-d',
            'promo_code' => 'required',
        ]);
    
        $validity = \Carbon\Carbon::parse($request->validity)->format('Y-m-d');
        $validity_end = \Carbon\Carbon::parse($request->validity_end)->format('Y-m-d');

        $result = Promo::updateOrCreate(
            ['id' => $request->id],
            [
                'title' => $request->title,
                // 'price' => $request->price,
                'validity' => $validity,
                'validity_end' => $validity_end,
                'promo_code' => $request->promo_code,
                'lab_id' => $request->lab_id,
            ]
        );
        // if (isset($request->image)) {
        //     $path = public_path("/uploads/package/");
        //     $uploadImg = $this->uploadDocuments($request->image, $path);
        //     $result->image = $uploadImg;
        //     $result->save();
        // }
    
        return redirect('promo')->with('success', 'Promo Code Created Successfully!');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {  
        $data['data'] = User::findOrFail($id);
        $data['appointments'] = Appointment::where('hospital_id',$id)->get();
        $data['doctor'] = HospitalDoctor::where('hospital_id',$id)->get();
        $data['title'] = "Promo Code";
        $data['page_name'] = "List";
        $get['abc'] = User::where('is_hospital',1)->get();
        $asd = HospitalDoctor::all();
        return view('admin.promo.show', $data,$get)->with('HospitalDoctor', $asd);;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

    public function edit($id)
    {
        $result['title'] = "Edit Promo Code";
        $result['page_name'] = "Edit";
        $result['states'] = State::all();
        $result["data"] = Promo::findOrFail($id);
        $result['labs'] = User::whereHas('roles', function ($query) {$query->where('id', 4);  })->get();
        if ($result["data"]) {
            return view("admin.promo.create", $result);
        } else {
            return redirect()
                ->back()
                ->with("error", "Data not found");
        }
    }

    
     public function hospital(Request $request)
    {
        
       $data= City::where('state_id', $request->state_id)->get();
    
        $outputData = '';
       
         if(isset($data) && count($data)>1){    
            foreach($data as $key => $value){
                if(!empty($value)){
                    $valueID = $value->id;
                    $valueTitle = $value->city;
                    $outputData .= "<option value='$valueID'>".$valueTitle."</option>";
                }
            }
         }
         return response()->json(['status' => true, 'outputData' => $outputData]);
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
        $data = Promo::find($id);
        $data->delete();
       return redirect()->back()->with('msg', 'Test Deleted Successfully');
    }

   
}
