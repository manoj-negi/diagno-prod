<?php

namespace App\Http\Controllers;

use App\Models\PresetQuestionAnswer;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use App\Models\Appointment;
use App\Models\AppointmentReport;
use App\Models\AppointmentBill;
use Illuminate\Support\Facades\Auth;
use Datatables;


use Hash;
use File;

class PatientController extends Controller
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
        // $q = Role::where('role', 'Patient')->first()->users();
        $data['title'] = "Patient";
        $data['page_name'] = "List";
        if ($request->ajax()) {
            $data = Role::where('role', 'Patient')->first()->users();
            return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('name', function($row){
                //   return $row;
               return isset($row->name) ? $row->name : '-';
                })
                ->addColumn('email', function($row){
                    //   return $row;
                   return isset($row->email) ? $row->email : '-';
                    })
                    ->addColumn('dob', function($row){
                        //   return $row;
                       return date('F j, Y', strtotime($row->dob));
                      
                        })
                        ->addColumn('status', function($row) {
                            return $row->status == 1 ? 'Active' : 'Inactive';
                        })
                        ->addColumn('action', function($row){
                            return  '
                            <a class="btn btn-primary btn-sm" href="'.route('patient.edit', $row->id).'">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </a>
                        
                            <form action="'.route('patient.destroy',$row->id).'" method="post" style="display: inline-block;">
                               
                                <a class=" text-white btn btn-danger  show_confirm btn-sm"  value="Delete">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>';
                       })
                        
                ->rawColumns(['name','email','dob','status','action'])
                ->make(true);
        }

        return view('admin.patient.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $result['title'] = "Patient";
        $result['page_name'] = "Create";
        $data = Role::all()->pluck('role', 'id');
        return view('admin.patient.create', compact('data'), $result);
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
            'name' => 'required',
            'email' => ['required', 'email', 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', 'unique:users,email,' . $request->id],
            'number' => 'required|numeric|digits_between:10,12',
            'address' => 'required',
          
            'dob' => 'required|date_format:Y-m-d'
        ]);

     

        $result = User::UpdateOrCreate([
            'id' => $request->id,

        ], [
                'name' => $request->name,
                'email' => $request->email,
                'number' => $request->number,
                'dob' => $request->dob,
                'address' => $request->address,
              
                'is_patient' => true,
                'status' => $request->status
            ]);
      

        if (isset($request->profile_image)) {

            $oldimage = $result->profile_image;
            if ($oldimage != 'user.png') {

                if (File::exists(public_path('/uploads/profile-imges/' . $oldimage)))
                    File::delete(public_path('/uploads/profile-imges/' . $oldimage));
            }

            $path = public_path('uploads/profile-imges');
            $uploadImg = $this->uploadDocuments($request->profile_image, $path);
            $result->profile_image = $uploadImg;
            $result->save();
        }

        $result->roles()->sync(2);
        if ($result) {
            if ($request->id) {
                return redirect()->route('patient.index')->with('msg', 'Patient is successfully updated');
            } else {
                return redirect()->route('patient.index')->with('msg', 'Patient is successfully created');
            }
        } else {
            return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $result['PatientReport'] = AppointmentReport::where('patient_id',$id)->get();
        $idArray = Appointment::where('patient_id', $id)->pluck('id')->toArray();
        $result['Patientbill'] = AppointmentBill::whereIn('appointment_id',$idArray)->get();
        $result['data'] = User::find($id);
        $result['title'] = "Patient";
        $result['page_name'] = "Profile";
        $records = getenv('ADMIN_PAGE_LIMIT');
        if (isset($_GET['paginate'])) {
            $records = $request->paginate;
        }
        $result['appointments'] = Appointment::where('patient_id', $id)->paginate($records)->withQueryString();

        //$result['preset_question_answer'] = PresetQuestionAnswer::with('Questions')->where('patient_id', $id)->get();

        $appointment_ids = Appointment::where('patient_id', $id)->pluck('id')->toArray();

        // echo json_encode($result); die();
        $result['reports'] = AppointmentReport::whereIn('appointment_id', $appointment_ids)->get();
        return view('admin.patient.view', $result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result['patient'] = User::findOrFail($id);
        $result['title'] = "Patient";
        $result['page_name'] = "Edit";
        if ($result['patient']) {
            return view('admin.patient.create', $result);
        } else {
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
        $data = User::find($id);
        $data->delete();
        return redirect()->route('patient.index');
    }
}