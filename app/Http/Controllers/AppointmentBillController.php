<?php
namespace App\Http\Controllers;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\AppointmentBill;
use App\Models\User;
use Datatables;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AppointmentBillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $data['page_name'] = "List";
        $data['title'] = 'Booking Bill';
    
        // $q = AppointmentBill::query();
        
        // if ($request->has('search')) {
        //     $q->where('title', 'like', '%' . $request->search . '%');  
        // }
        // if (!empty($request->hospital)) {
        //     $q->whereHas('appointment', function($q) use ($request)
        //     {
        //         $q->where('hospital_id', $request->hospital);   
        //     });
        // }
        // $data['hospital'] = User::where('is_hospital', true)->get();

        // $data['data'] = $q->orderBy('id', 'DESC')
        //                   ->paginate($request->get('pagination', 10)) 
        //                   ->withQueryString(); 


        $q = AppointmentBill::query(); 
        if ($request->ajax()) {
            $data = AppointmentBill::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function($row){
                    return isset($row->title) ? $row->title :'-';
                 })
                ->addColumn('lab', function($row){
                    return isset($row->appointment->hospital->name) ? $row->appointment->hospital->name :'-';
                 })
                ->addColumn('appointment_id', function($row){
                    return isset($row->appointment_id) ? $row->appointment_id :'-';
                 })
                ->addColumn('amount', function($row){
                    return isset($row->amount) ? $row->amount :'-';
                 })
                 ->addColumn('document_file', function($row) {
                    if($row->document_file) {
                        return '<a href="'. url('uploads/appointmentbill/' . $row->document_file) . '" download>
                                    <button type="button" class="btn btn-primary btn-sm">Download</button>
                                </a>';
                    } else {
                        return ''; // Return an empty string if no document file exists
                    }
                })
                  ->addColumn('action', function($row){
                    return 
                    '
                    <a href="'.route('appointments-bills.edit', $row->id).'" class="btn btn-primary btn-sm">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
                    <form action="'.route('appointments-bills.destroy',$row->id).'" method="POST" style="display: inline-block;">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="_method" value="delete">
                            <a class=" text-white btn btn-danger  show_confirm btn-sm"  value="Delete">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>';
                })
                ->rawColumns(['action','title','appointment_id','name','amount','document_file'])
                ->make(true);
        }
            if(Auth::user()->roles->contains(4)){
                $q->where('hospital_id',Auth::user()->id);
            }
                       
        return view('admin.appointmentbill.index', $data);
    }
    
    
    public function create()
    {
        $data['page_name'] = "Create";
        
        $data['title'] = 'Add Booking Bill';
        $data['hospital_appointment'] = Appointment::all();
        // if(Auth::user()->roles->contains(4)){
        //  $data['hospital_appointment']->where('hospital_id',Auth::user()->id);
        // }
        return view('admin.appointmentbill.create',$data);
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
      
        'title' => 'required',
        'appointment_id' => 'required',
        'amount'=>'required|numeric|min:0',
    ]);

    $path = 'uploads/appointmentbill';
    $documentFile = !empty($request->document_file)
        ? $this->uploadDocuments($request->document_file, $path)
        : $request->old_document_file;

    AppointmentBill::updateOrCreate(
        ['id' => $request->id],
        [
            'appointment_id' => $request->appointment_id,
            'amount'         => $request->amount,
            'title'          => $request->title,
            'document_file'  => $documentFile,
        ]
    );

    $msg = isset($request->id) && !empty($request->id) ? 'Updated Successfully.' : 'Created Successfully';

    return redirect()->route('appointments-bills.index')->with('data_created', $msg);
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result['page_name'] = "View";
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {  $categories['hospital_appointment'] = Appointment::all();
        $categories['title'] = 'Edit Booking';
        $categories['page_name'] = "Edit";
        $categories['data'] = AppointmentBill::find($id);
        return view('admin.appointmentbill.create',$categories);
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
        $data = AppointmentBill::find($id);
        $data->delete();

        if ($data) {
            return redirect()->route('appointments-bills.index')->with('msg', 'Booking Bill is successfully Deleted');
        } else {
            return redirect()->route('appointments-bills.index')->with('error', 'Booking Bill successfully Deleted');
        }
}
}


