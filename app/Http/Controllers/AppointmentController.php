<?php
namespace App\Http\Controllers;
use App\Models\PatientQuestionAnswer;
use App\Models\PresetQuestionAnswer;
use Datatables;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Education;
use App\Models\AppointmentReport;
use App\Models\User;
use App\Models\Role;
use App\Models\AppointmentBill;
use App\Models\AppointmentTracking;
use App\Models\TestRequest;
use App\Models\settings;
use App\Mail\SendMail;
use Carbon\Carbon;
use Validate;
use File;
use Auth;
use Mail;
use DB;
use App\Models\MailTemplate;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index(Request $request)
     {
        

         $data['page_name'] = "List";
         $data['title'] = 'Bookings';
         $q = Appointment::orderBy('id','desc');
         $q->when($request->filled('search'), function ($query) use ($request) {
            $query->whereHas('hospital', function ($q) use ($request) {
                $q->where('name', $request->search);
            })->orWhereHas('patient', function ($q) use ($request) {
                $q->where('name', $request->search);
            });
        });
    
        $q->when($request->filled('status'), function ($query) use ($request) {
            $query->where('status', $request->status);
        });
       if (!empty($request->lab)) {
           $q->where('hospital_id', $request->lab);
       }
       if (!empty($request->start_date) && !empty($request->end_date)) {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $q->whereBetween('created_at', [$startDate, $endDate]);
    }
       $data['labs'] = User::whereHas('roles', function($q){$q->where('id','=', 4);})->get();
       $data['data'] = $q->orderBy('id', 'DESC')->paginate($request->input('pagination', 10))->withQueryString();
    
        return view('admin.appointments.index', $data);

        //  if ($request->ajax()) {
        //     $data = Appointment::with('hospital','test','patient');
        //     if(Auth::user()->roles->contains(4)){
        //         $data->where('hospital_id',Auth::user()->id);
        //      }
             
        //     $dataTable = Datatables::of($data)
        //         ->addIndexColumn()
        //         ->addColumn('name', function($row){
        //            return isset($row->patient) ? $row->patient->name : '-';
        //             })
        //                 ->addColumn('test_type', function($row){
        //                   return $row->test_type ?? '';
        //                 })
        //                 ->addColumn('booking_date', function($row){
        //                   return date('d F Y H:i A',strtotime($row->created_at));
        //                 })
        //                 ->addColumn('hospital_id', function($row){
        //                     if(Auth::user()->roles->contains(1)){
        //                     return  isset($row->hospital) ? $row->hospital->name : '-';
        //                 }
        //                 else{
        //                     return '';
        //                 }
        //                 })->addColumn('status', function($row){
        //                         return '<form action="'.url('appointments-status-update', $row->id).'" method="post">
        //                         <input type="hidden" name="_token" value="'.csrf_token().'">
        //                             <select name="status" class="form-control onChangeStatusSelect" style="color: #697a8d!important;">
        //                                 <option value="complete" '.($row->status == 'complete' ? 'selected' : '').'>Complete</option>
        //                                 <option value="pending" '.($row->status == 'pending' ? 'selected' : '').'>Pending</option>
        //                                 <option value="collect" '.($row->status == 'collect' ? 'selected' : '').'>Collect</option>
        //                             </select>
                                   
        //                         </form>';
        //                     })
        //                     ->addColumn('report_image', function($row) {
        //                         return ' <input type="hidden" name="_token" value="'.csrf_token().'">
        //                         <button type="button" class="btn btn-xs btn-primary upload-button" data-bs-target="#exampleModalupload" user-id="'.$row->id.'">
        //                             Upload
        //                         </button>';
        //                     })
        //                     ->addColumn('action', function($row) {
        //                         $action = '
        //                             <a class="btn btn-primary btn-sm" href="'.route('appointments.show', $row->id).'">
        //                                 <i class="fa fa-eye" aria-hidden="true"></i>
        //                             </a>';
        //                         if(Auth::user()->roles->contains(4)) {
        //                             $action .= '
        //                                 <a class="btn btn-primary btn-sm" href="' . route('test-request.create', [
        //                                     'name' => isset($row->patient) ?  $row->patient->name : '',
        //                                     'age' => isset($row->patient) ?  $row->patient->age : '',
        //                                     'gender' => isset($row->patient) ?  $row->patient->sex : '',
        //                                     'number' => isset($row->patient) ?  $row->patient->number : '',
        //                                     'address' => isset($row->patient) ?  $row->patient->address : '',
        //                                 ]) . '">
        //                                     <i class="fa fa-backward" aria-hidden="true"></i>
        //                                 </a>';
        //                         }
        //                         $action .= '
        //                             <form action="' . route('appointments.destroy', $row->id) . '" method="POST" style="display: inline-block;">
        //                                 <input type="hidden" name="_token" value="'.csrf_token().'">
        //                                 <input type="hidden" name="_method" value="delete">
        //                                 <button type="submit" class="text-white btn btn-danger show_confirm btn-sm">
        //                                     <i class="fa fa-trash" aria-hidden="true"></i>
        //                                 </button>
        //                             </form>';
        //                         return $action;
        //                     })
        //         ->rawColumns(['name','hospital_id','report_title','status','report_image','action']);
        //         if ($request->has('search') && !empty($request->search['value'])) {
        //             $searchValue = $request->search['value'];
        //             $dataTable->filter(function($query) use ($searchValue) {
        //                 $query->whereHas('hospital', function($subquery) use ($searchValue) {
        //                     $subquery->where('name', 'like', '%'.$searchValue.'%');
        //                 })
        //                 ->orWhereHas('test', function($subquery) use ($searchValue) {
        //                     $subquery->where('test_name', 'like', '%'.$searchValue.'%');
        //                 })
        //                 ->orWhereHas('patient', function($subquery) use ($searchValue) {
        //                     $subquery->where('name', 'like', '%'.$searchValue.'%');
        //                 })
        //                 ->orWhere('id', 'like', '%'.$searchValue.'%');
        //                 // ->orWhere('status', 'like', '%'.$searchValue.'%')
        //                 // ->orWhere('contact', 'like', '%'.$searchValue.'%')
        //                 // ->orWhere('patient_id', 'like', '%'.$searchValue.'%')
        //             });
        //         }
    
        //         return $dataTable->make(true);
        // }
        //  if(Auth::user()->roles->contains(4)){
        //     $q->where('hospital_id',Auth::user()->id);
        //  }
  
     }
     
   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Booking";
        $data['page_name'] = "Create";
        // $data['doctor'] =  Role::where('role', 'hospital')->first()->users()->get();
        $data['patient'] =  Role::where('role', 'patient')->first()->users()->get();
       
        return view('admin.appointments.create', $data);
    }
    public function appointmentUpdate(Request $request, $id)
    {
        $data = Appointment::where('id', $id)->first();
    
        if ($data) {
            $data->status = $request->status;
            $data->save();
    
            AppointmentTracking::create([
                'appointment_id' => $data->id,
                'status' => $data->status,
            ]);
    
            return redirect()->back()->with('msg', 'Status updated successfully');
        } else {
            return redirect()->back()->with('error', 'Booking not found');
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    

     

     public function store(Request $request)
     {
         // Validate the incoming request
         $validatedData = $request->validate([
             
         ]);
     
         // Get the current authenticated user's ID
         $userId = Auth::id();
     
         // Store the test request
         $testRequest = TestRequest::updateOrCreate(
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
                 'appointment_id' => $request->appointment_id,
                 
             ]
         );
     
         // Check if the test request was stored successfully
         if ($testRequest) {
             $emailSetting = settings::where('key', 'email')->first();
             $emails = $emailSetting->value;
             $mailData = MailTemplate::where('category', 'contact-us')->first();
             $basicInfo = [
                 '{name}' => $request->patient_name,
                 '{age}' => $request->age,
                 '{test}' => $request->test_type,
                 '{gender}' => $request->gender,
                 '{number}' => $request->contact,
                 '{status}' => $request->payment_status,
                 '{amount}' => $request->amount,
                 '{address}' => $request->address,
                 '{message}' => '', // You haven't defined $message in your code
                 '{siteName}' => '', // You need to define your site name
             ];
             $this->SendMail($emails, $mailData, $basicInfo);
     
             return redirect()->route('test-request.index')->with('success', 'Your Message has been received. Thank you for contacting us!');
         } else {
             return redirect()->back()->with('error', 'Failed to store the test request. Please try again.');
         }
     }
     
    
    //  public function updatestatus($id)
    //     {
    //         $user = Appointment::find($id)->update(['status' => 1]);
    //         return redirect()->back()->with('msg', 'Patient Status Updated successfully');
    //     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $result['PatientReport'] = AppointmentReport::where('patient_id',$id)->get();
        $result['appoimentbill'] = AppointmentBill::where('appointment_id',$id)->get();
        $result['appoinment'] = AppointmentTracking::where('appointment_id',$id)->paginate(10);
        $result['title'] = "Booking";
        $result['page_name'] = "View";
        $result['data'] = Appointment::findOrFail($id);
        return view('admin.appointments.view', $result);
    }


    public function reportFile(Request $request)
    {
        $request->validate([
            'user_id' => 'required', 
            'report_image' => 'required', 
        ]);

            if ($request->hasFile('report_image')) {
                $path = 'uploads/appointment';
                $file = $request->file('report_image');
                $appointment = Appointment::findOrFail($request->user_id);
                $appointment->report_image =$this->uploadDocuments($file, $path);
                // return $appointment;
                $appointment->save();
            }
            return redirect()->route('appointments.index')->with('msg', 'Report Uploaded Successfully');
    
        // $path = 'uploads/appointment';
        // $datas = Appointment::where('id',$request->user_id)->first();
        // $datas->report_image = $this->file($request->report_image,$path);
        // $datas->save();
        // return redirect()->route('appointments.index')->with('msg', 'Report Uploaded Successfully');   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result['title'] = "Appointment";
        $result['page_name'] = "Edit";
        $result['data'] = Appointment::findOrFail($id);

        $result['doctor'] = User::where('is_hospital', 1)->pluck('name', 'id');
        $result['patient'] = User::where('is_patient', 1)->pluck('name', 'id');
        // return $result;
        return view('admin.appointments.create', $result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Appointment::find($id);
        $data->delete();

        if ($data) {
            return redirect()->route('appointments.index')->with('msg', 'Hospital is successfully Deleted');
        } else {
            return redirect()->route('appointments.index')->with('error', 'Hospital successfully Deleted');
        }

    }
}