<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\HospitalCategory; 
use File;
use Datatables;
use App\Models\Role;
use App\Models\Doctor_hospital; 
use App\Models\User;
use App\Mail\SendMail;
use Auth;
use Mail;
use App\Models\MailTemplate;
use App\Models\State;
use App\Models\settings;
use App\Models\hospitalsReport;
use App\Models\City;
use App\Models\LabTest;
use App\Models\HospitalDoctor;
use App\Models\PatientReport;
use App\Models\TestRequest;
use App\Models\Appointment;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\OrderShipped;
// use Mail;
class TestRequestController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    $data['title'] = "Lab Request";
    $data['page_name'] = "List";
    
     // if(Auth::user()->roles->contains(4)){
    //     $data['labtest']->where('hospital_id',Auth::user()->id);
    // }

    $q = TestRequest::query(); 
    if ($request->ajax()) {
        // $data = TestRequest::query();
        $data = TestRequest::with('hospital'); 
        if(Auth::user()->roles->contains(4)){
            $data->where('lab_id',Auth::user()->id)->orderBy('id','desc');
         }else{
            $data->orderBy('id','desc');
         }
         $dataTable = Datatables::of($data)
            ->addIndexColumn()
            // ->addColumn('patient_name', function($row){
            //     return isset($row->patient_name) ? $row->patient_name :'-';
            //  }) 
            ->addColumn('lab_id', function($row){
                if(Auth::user()->roles->contains(1)){
                    return  isset($row->hospital) ? $row->hospital->name : '-';
                }
                else{
                    return '';
                }
             })
            //  ->addColumn('contact', function($row){
            //     return isset($row->contact) ? $row->contact :'-';
            //  })
             ->addColumn('amount', function ($row) {
                return isset($row->amount) ? '₹'. $row->amount : '-';
            })
              ->addColumn('action', function($row){
                return 
                '
                 <form action="'.route('test-request.destroy',$row->id).'" method="POST" style="display: inline-block;">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="_method" value="delete">
                        <a class=" text-white btn btn-danger  show_confirm btn-sm"  value="Delete">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>';
            })
            ->rawColumns(['patient_name','lab_id','amount','contact','action']);

            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $dataTable->filter(function($query) use ($searchValue) {
                    $query->whereHas('hospital', function($subquery) use ($searchValue) {
                        $subquery->where('name', 'like', '%'.$searchValue.'%');
                    })
                    ->orWhere('id', 'like', '%'.$searchValue.'%')
                    ->orWhere('patient_name', 'like', '%'.$searchValue.'%')
                    ->orWhere('contact', 'like', '%'.$searchValue.'%')
                    ->orWhere('amount', 'like', '%'.$searchValue.'%');
                });
            }

            return $dataTable->make(true);
    }
        if(Auth::user()->roles->contains(4)){
            $q->where('hospital_id',Auth::user()->id);
        }

   

    return view('admin.test-request.index', $data);
}

    public function updatestatuss(Request $request, $id)
    {
        $data = LabTest::find($id);
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
        // $result['HospitalCategory'] = HospitalCategory::all();
        $result['title'] = "Test Request";
        $result['page_name'] = "Create";
        $result['labs'] = User::whereHas('roles', function($q) {
            $q->where('id', 4);
        })->get();
        // $result['State'] = State::all();
        $result['abc'] = User::where('is_hospital',1)->get();
        return view('admin.test-request.create', $result);
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
        'patient_name' => 'required',
        'age' => 'required|numeric|min:1|max:100',
        'gender' => 'required',
        'contact' => 'required|numeric|digits_between:10,12',
        'address' => 'required',
        'payment_status' => 'required',
        'test_type' => 'required',
    ]);

    $userId = Auth::id();

    $result = TestRequest::updateOrCreate(
        ['id' => $request->id],
        [
            'lab_id' => $userId,
            'patient_name' => $request->patient_name,
            'age' => $request->age,
            'gender' => $request->gender,
            'contact' => $request->contact,
            'address' => $request->address,
            'amount' => $request->amount,
            'test_type' => $request->test_type,
            'payment_status' => $request->payment_status,
        ]
    );


    
    if($result) {
        $email = settings::where('key','email')->select('value')->first();
        $emails = $email->value;
        // return $emails;
        // $email = 'raoabhiyadav323@gmail.com';
        // $data = ['key' => 'value']; 
        // $mailData = MailTemplate::where('category', 'contact-us')->first();
        // $basicInfo = [
        //     '{name}' => ($result->patient_name)??'',
        //     '{age}' => ($result->age)??'',
        //     '{test}' => ($result->test_type)??'',
        //     '{gender}' => ($result->gender)??'',
        //     '{number}' => ($result->contact)??'',
        //     '{status}' => ($result->payment_status)??'',
        //     '{amount}' => ($result->amount)??'',
        //     '{address}' => ($result->address)??'',
        //     '{message}' => ($message)??'',
        //     '{siteName}' => '',
        // ];
        // $this->SendMail($emails, $mailData, $basicInfo);

        return redirect()->route('test-request.index')->with('Your Message has received. Thank you to contact us!');
    }


    // $resultArray = $result->toArray();

    // Mail::send(['text'=>'mail'], $resultArray, function($message)  {
    //     $message->to('omipc07@gmail.com', 'Tutorials Point')->subject('New Request For Lab Test');
    //     $message->from('tapang786@gmail.com','Diagno Mitra');
    //  });

    if ($result) {
        $message = $request->id ? 'Test Request Send Successfully' : 'Test Request Send Successfully';
        return redirect()->route('test-request.index')->with('msg', $message);
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
        $data['data'] = User::findOrFail($id);
        $data['appointments'] = Appointment::where('hospital_id',$id)->get();
        $data['doctor'] = HospitalDoctor::where('hospital_id',$id)->get();
        $data['title'] = "Lab";
        $data['page_name'] = "List";
        $get['abc'] = User::where('is_hospital',1)->get();
        $asd = HospitalDoctor::all();
        return view('admin.lab-test.show', $data,$get)->with('HospitalDoctor', $asd);;
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
        $data['states'] = State::all();
      
        $data['result'] = LabTest::findOrFail($id);
    
        return view('admin.lab-test.create', $data);
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
        $data = TestRequest::find($id);
        $data->delete();
        return redirect()->route('test-request.index');
    }

   
}
