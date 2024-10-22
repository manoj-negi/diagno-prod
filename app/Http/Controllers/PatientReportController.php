<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\HospitalCategory;
use File;
use Illuminate\Support\Facades\Hash;
use App\Models\Doctor_hospital;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Storage;
use App\Models\AppointmentReport;
use Datatables;
use Auth;

class PatientReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $data['title'] = 'Patient';
        $data['page_name'] = "Create";
    
        if ($request->ajax()) {
            $query = Appointment::orderBy('id','desc')->whereNotNull('report_image');
    
            // You should modify the $query object, not $data
            if(Auth::user()->roles->contains(4)){
                $query->where('hospital_id',Auth::user()->id);
            }
    
            $appointments = $query->get();
    
            return Datatables::of($appointments)
                ->addIndexColumn()
                ->addColumn('name', function($row) {
                    return isset($row->patient) ? $row->patient->name : '-';
                })
                ->addColumn('hospital_id', function($row) {
                    if(Auth::user()->roles->contains(1)) {
                        return isset($row->hospital) ? $row->hospital->name : '-';
                    } else {
                        return '';
                    }
                })
                ->addColumn('test_id', function($row) {
                    return isset($row->test) ? $row->test->test_name : '-';
                })
              
               
                ->rawColumns(['name', 'hospital_id', 'status', 'report_image', 'action', 'test_id'])
                ->make(true);
        }
       
        return view('admin.patientreport.index', $data);
    }
    

    
    public function create()
    {
        $da['appointment'] = Appointment::all();
        $data['Patient'] = User::where('is_patient', true)->get();
        $data['hospital'] = User::where('is_hospital',true)->get();
        $data['title'] = "Patient Report";
        $data['page_name'] = "Create";
        return view('admin.patientreport.create', $data,$da);
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
            'report_image'       => 'mimes:jpg,jpeg,png',
        ]);

        $path = 'uploads/patientreport';
        $documentFile = !empty($request->report_image)
            ? $this->uploadDocuments($request->report_image, $path)
            : $request->old_report_image;
       
        AppointmentReport::updateOrCreate([
            'id'  => $request->id,
        ],[
            // 'appointment_id'  => $request->appointment_id,
            'patient_id'     => $request->patient_id,
            'report_title'  => $request->report_title,
            'report_image'=>  $documentFile,
            // $file = Storage::disk('public')->path($filename),
            // 'status'       => $request->status,
        ]);

        $msg = isset($request->id) && !empty($request->id)?'Updated Successfully.':'Created Successsfully';

        return redirect()->route('patient-report.index')->with('data_created',$msg, response());
                       
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
        $data['appointment'] = Appointment::all();

        $data['title'] = "Edit patient Report";
        $data['page_name'] = "Edit";
        $data['Patient'] = User::all();
        $data['hospital'] = User::where('is_hospital',true)->get();
        $data['result'] = AppointmentReport::findOrFail($id);
        
        if($data['result'])
        {
            return view('admin.patientreport.create',$data);
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
    public function update()
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
        $data = AppointmentReport::find($id);
        $data->delete();

        if($data)
            {
                return redirect()->route('patient-report.index')->with('msg','Hospital is successfully Deleted'); 
            }
            else
            {
                return redirect()->route('patient-report.index')->with('error','Hospital successfully Deleted'); 
            }
    }

}
