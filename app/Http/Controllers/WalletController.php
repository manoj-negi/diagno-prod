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
use Auth;
use App\Models\State;
use App\Models\Wallet;
use App\Models\hospitalsReport;
use App\Models\City;
use App\Models\LabTest;
use App\Models\HospitalDoctor;
use App\Models\PatientReport;
use App\Models\Appointment;
use App\Models\Package;
use App\Models\LabSelectedPackages;
use Illuminate\Support\Facades\Hash;

class WalletController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    $data['title'] = "Wallet";
    $data['page_name'] = "List";
    $data['labs'] = User::whereHas('roles', function($q){$q->where('id','=', 4);})->get();

    $dataTest = Wallet::query();

if(!empty($request->search)){
    $dataTest->where('user_id', 'LIKE', '%' . $request->search . '%');
}
// if(!empty($request->lab)){
//     $dataTest->where('lab_id', $request->lab);
// }
// if(Auth::user()->roles->contains(4)){
//     $dataTest->where('lab_id', Auth::user()->id);
// }

$data['data'] = $dataTest->orderBy('id', 'DESC')->paginate($request->pagination ?? 10)->withQueryString();

    // if ($request->ajax()) {
    //     $data = Package::where('type','test');
    //     if(Auth::user()->roles->contains(4)){
    //         $data->where('lab_id',Auth::user()->id);
    //     }elseif(Auth::user()->roles->contains(1)){
    //         $data->where('lab_id','!=',Auth::user()->id);
    //     }
    //     $data->orderBy('id','desc')->get();
    //      $dataTable = Datatables::of($data)
    //         ->addIndexColumn()
    //          ->addColumn('amount', function ($row) {
    //             return isset($row->amount) ? '₹ ' . $row->amount : '-';
    //         })
    //            ->addColumn('lab', function($row){
    //             if(Auth::user()->roles->contains(1)){
    //                 if($row->lab_id != Auth::user()->id){
    //                     return  isset($row->labDetails) ? $row->labDetails->name : '-'; 
    //                 }else{
    //                     return 'Diagno Mitra';
    //                 }

    //             }elseif(Auth::user()->roles->contains(4)){
    //                 if(Auth::user()->id == $row->lab_id){
    //                     return Auth::user()->name ?? '-';
    //                 }
    //                 return 'Diagno Mitra';

    //             }
    //         })
    //           ->addColumn('action', function($row){
    //             if(Auth::user()->roles->contains(1)){
    //             $data = '';
    //             $data .= '<a href="'.route('wallet.edit', $row->id).'?url=wallet" class="btn btn-primary btn-sm mr-1">
    //                 <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
    //             </a>';

    //             $data .= '<form action="'.route('wallet.destroy',$row->id).'" method="POST" style="display: inline-block;">
    //                 <input type="hidden" name="_token" value="'.csrf_token().'">
    //                 <input type="hidden" name="_method" value="delete">
    //                     <a class=" text-white btn btn-danger  show_confirm btn-sm"  value="Delete">
    //                         <i class="fa fa-trash" aria-hidden="true"></i>
    //                     </a>';

    //             return $data;
    //             }elseif(Auth::user()->roles->contains(4) && Auth::user()->id == $row->lab_id){
    //                 $data = '';
    //             $data .= '<a href="'.route('wallet.edit', $row->id).'" class="btn btn-primary btn-sm">
    //                 <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
    //             </a>';

    //             $data .= '<form action="'.route('wallet.destroy',$row->id).'" method="POST" style="display: inline-block;">
    //                 <input type="hidden" name="_token" value="'.csrf_token().'">
    //                 <input type="hidden" name="_method" value="delete">
    //                     <a class=" text-white btn btn-danger  show_confirm btn-sm"  value="Delete">
    //                         <i class="fa fa-trash" aria-hidden="true"></i>
    //                     </a>';

    //             return $data;
    //             }
    //             return '';  
                
    //         })
    //         ->rawColumns(['action','package_name','description','lab']);
    //         if ($request->has('search') && !empty($request->search['value'])) {
    //             $searchValue = $request->search['value'];
    //             $dataTable->filter(function($query) use ($searchValue) {
    //                 $query->Where('package_name', 'like', '%'.$searchValue.'%')
    //                 ->orWhere('amount', 'like', '%'.$searchValue.'%');
    //             });
    //         }

    //         return $dataTable->make(true);
    // }
    return view('admin.wallet.index', $data);
    }
    public function indexDiagno(Request $request)
    {
    $data['title'] = "Lab Test";
    $data['page_name'] = "List";
    $dataTest = Package::where('type','test')->where('lab_id',1);
  
    if(!empty($request->search)){
        $dataTest->where('package_name', 'LIKE', '%' . $request->search . '%');
    }
    if(!empty($request->lab)){
        $dataTest->where('lab_id',$request->lab);
    }
  
    $data['data'] = $dataTest->orderBy('id','DESC')->paginate($request->pagination ?? 10)->withQueryString();
    return view('admin.wallet.index', $data);
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
        $result['title'] = "Lab";
        $result['page_name'] = "Create";
        $result['labs'] = User::whereHas('roles', function ($query) {$query->where('id', 4);  })->get();
        return view('admin.wallet.create', $result);
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
        'test_name' => 'required',
        'amount' => 'required',
    ]);

    $userId = Auth::id();
    $labID = $request->lab_id ?? 1;
    if(Auth::user()->roles->contains(4)){
        $labID = Auth::user()->id;
    }
    $result = Package::updateOrCreate(
        ['id' => $request->id],
        [
            'package_name' => $request->test_name ?? '',
            'amount' => $request->amount ?? '',
            'lab_id' =>  $labID,
            'description' => $request->description ?? '',
            'type'        => 'test',
        ]
    );
    if($labID != 1){
        LabSelectedPackages::updateOrcreate(
            [ 'package_id' => $result->id]
            ,[
            'lab_id' => $labID,
            'amount' => $request->amount ?? '',
            'is_selected' => true,
        ]);
    }
    
    if ($result) {
        $message = $request->id ? 'Test Successfully Updated' : 'Test Successfully Created';
        return redirect()->TO($request->url)->with('msg', $message);
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
        return view('admin.wallet.show', $data,$get)->with('HospitalDoctor', $asd);;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

    public function edit($id)
    {
        $result['title'] = "Edit Lab";
        $result['page_name'] = "Edit";
        $result['states'] = State::all();
        $result["data"] = Package::findOrFail($id);
        $result['labs'] = User::whereHas('roles', function ($query) {$query->where('id', 4);  })->get();
        if ($result["data"]) {
            return view("admin.wallet.create", $result);
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
        $data = Package::find($id);
        $data->delete();
       return redirect()->back()->with('msg', 'Test Deleted Successfully');
    }

   
}
