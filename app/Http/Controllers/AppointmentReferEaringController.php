<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AppointmentReferEaring;

class AppointmentReferEaringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $records = getenv('ADMIN_PAGE_LIMIT');
    
            $search = $request->search;
    
           
    
            if (isset($_GET['paginate'])) {
                $records = $request->paginate;
            }
    
    
           
            // if (!empty($request->search)) {
            //     $q->where('amount', 'like', '%' . $request->search . '%');
            // }
            // if (!empty($request->refer)) {
            //     $q->where('refer_code', $request->refer);
            // }
            
            $q = User::whereNotNull('refer_code');
             $data['data'] = $q->paginate($records)->withQueryString();
    
            $data['title'] = "Master Reference Code";
            $data['page_name'] = "List";
    
            return view('admin.appointmentreferearing.index', $data);
    
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        

        $data['refercode'] = User::all();
        $data['hospital_appointment'] = Appointment::all();
        $data['title'] = "Master Reference Code";
        $data['page_name'] = "Create";
        return view('admin.appointmentreferearing.create', $data);
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
            'appointment_id' => 'required',
            'refer_code' => 'required',
            'amount' => 'required',

           
        ]);

        $data = AppointmentReferEaring::UpdateOrCreate([
            'id' => $request->id,
        ],
        [
            'appointment_id' => $request->appointment_id,
            'refer_code' => $request->refer_code,
            'amount' => $request->amount,
        ]);
        if($data)
        {
            if($request->id)
            {
                return redirect()->route('appointments-refer.index')->with('msg','Appointments Refer is successfully updated'); 
            }
            else
            {
                return redirect()->route('appointments-refer.index')->with('msg','Appointments Refer is successfully created'); 
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
        $data['page_name'] = "Show";
        $data['title'] = "Master Reference Code";
        $refer = AppointmentReferEaring::where('refer_code',$id)->paginate(20);
        return view('admin.appointmentreferearing.show',compact('refer','id'),$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result['refercode'] = User::all();
        $result['hospital_appointment'] = Appointment::all();
        $result['title'] = "Master Reference Code";
        $result['page_name'] = "Edit";
        $result['data'] = AppointmentReferEaring::findOrFail($id);
        if($result['data'])
        {
            return view('admin.appointmentreferearing.create',$result);
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
        $data = AppointmentReferEaring::find($id);
        $data->delete();
        if($data)
            {
                return redirect()->route('appointments-refer.index')->with('msg','Appointments Refer is successfully Deleted'); 
            }
            else
            {
                return redirect()->route('appointments-refer.index')->with('error','Appointments Refer unsuccessfully Deleted'); 
            }
    }
}
