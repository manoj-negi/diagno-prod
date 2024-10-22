<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\HospitalDoctor;
use App\Models\HospitalCategory;

use App\Models\User;
use App\Models\Role;


class HospitalDoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['title'] = 'HospitalDoctors';
        $q = HospitalDoctor::query();
    
        if (!empty($request->search)) {
            $q->whereHas('hospital', function ($q) use ($request) {
                $q->where('name', '=', $request->search);
            })->orWhere('doctor_name', 'like', '%' . $request->search . '%')
              ->orWhere('doctor_age', 'like', '%' . $request->search . '%')
              ->orWhere('doctor_category', 'like', '%' . $request->search . '%');
        }
        if (!empty($request->hospital)) {
            $q->where('hospital_id', $request->hospital);
        }
        
    if (!empty($request->category)) {
        $q->where('doctor_category', $request->category);
    }
        $q->orderBy('id', 'DESC');  
        $data['hospital'] = User::where('is_hospital', 1)->get();
        $data['hospital_category'] = HospitalCategory::all();
        $data['page_name'] = "List";
        $data['data'] = $q->paginate(isset($request->pagination) && !empty($request->pagination) ? $request->pagination : 10)->withQueryString();
    
        return view('admin.HospitalDoctor.index', $data);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {    

        $data['page_name'] = "Create";
        $data['title'] = 'Add HospitalDoctors';
        $data['doctor_category'] = HospitalCategory::all();
        $get['hospital_report'] = Role::where('role', 'hospital')->first()->users()->get();
        
        return view('admin.HospitalDoctor.create',$data, $get);
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
            'hospital_id' => 'required',
            'doctor_name' => 'required',
            'doctor_age'=>'required|numeric|min:18|max:100',
            'doctor_category'=>'required',
            'image'       => 'mimes:jpg,jpeg,png',
        ]);

        $path = 'uploads/doctors';
      $result =   HospitalDoctor::updateOrCreate([
            'id'  => $request->id,
        ],[
            'type'=>'HospitalDoctors',
            'hospital_id'=> $request->hospital_id,
            'doctor_name'=>$request->doctor_name,
            'doctor_age'=>$request->doctor_age,
            'doctor_category'=>$request->doctor_category,
            'image'        => !empty($request->image) ? $this->uploadDocuments($request->image,$path) : 
            $request->old_image,
        ]);
        if ($result) {
            if ($request->id) {
                return redirect()
                    ->route("hospital-doctors.index")
                    ->with("msg", "successfully updated");
            } else {
                return redirect()
                    ->route("hospital-doctors.index")
                    ->with("msg", "successfully created");
            }
        } else {
            return redirect()
                ->back()
                ->with("error", "Something went Wrong, Please try again!");
        }
        return redirect()->route('hospital-doctors.index')->with('data_created');
                       
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
        $categories['title'] = 'Edit Hospital Doctors';
        $categories['page_name'] = "Edit";
        $get['hospital_report'] = Role::where('role', 'hospital')->first()->users()->get();
        $categories['doctor_category'] = HospitalCategory::all();
        $categories['data'] = HospitalDoctor::find($id);
        return view('admin.HospitalDoctor.create',$categories,$get);
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
        HospitalDoctor::find($id)->delete();
        return redirect()->route('hospital-doctors.index');
    }
}
