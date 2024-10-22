<?php

namespace App\Http\Controllers;

use App\Models\TypeOfConsultation;
use Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Avaliablity;
use App\Models\Role;
use App\Models\Hospital;
use App\Models\Education;
use App\Models\Speciality;
use App\Models\Appointment;
use App\Models\SubscriptionInventory;
use App\Models\RatingReview;
use App\Models\questions;

use Carbon\Carbon;
use DB;
use Hash;
use File;
use DateTime;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $title = "Doctor";
        $page_name = "List";

        $records = getenv('ADMIN_PAGE_LIMIT');

        $query = User::whereHas('roles', function ($q) {
            $q->where('role', '=', 'doctor');
        });

        if (isset($_GET['paginate'])) {
            $records = $request->paginate;
        }

        if ($request->search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }
        if (isset($request->filter)) {
            $query->where('is_approved', $request->filter);

        }
        if (isset($request->status)) {
            $query->where('status', $request->status);

        }

        $doctorData = $query->orderBy('id', 'desc')->paginate($records);

        return view('admin.doctor.index', compact('doctorData', 'page_name', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Doctor";
        $data['page_name'] = "Create";

        $result['speciality'] = Speciality::all()->pluck('name', 'id');
        $result['education'] = Education::all()->pluck('name', 'id');
        $data['hospitals'] = Hospital::all()->pluck('name', 'id');
        $result['roles'] = User::whereHas('roles', function ($q) {
            $q->where('role', '=', 3);
        })->get();

        return view('admin.doctor.create', $result, $data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'email' => ['required', 'email', 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', 'unique:users,email,' . $request->id],
            'name' => 'required',
            'number' => 'required|numeric|digits:10',
            // 'education' => 'required',
            'speciality' => 'required',
            'hospital' => 'required',
            'exequatur_number' => 'required',
        ]);

        try {

            $data = User::UpdateOrCreate([
                'id' => $request->id,
            ], [
                'name' => $request->name ?? '',
                'email' => $request->email ?? '',
                'number' => $request->number ?? '',
                'address' => $request->address ?? '',
                'status' => $request->status ?? '',
                'experience' => $request->experience ?? '',
                'is_approved' => $request->is_approved ?? '',
                'is_virtual' => $request->is_virtual ?? '',
                'is_doctor' => 1 ?? '',
                'by_turn_time' => $request->by_turn_time ?? '',
                'doctor_bio' => $request->doctor_bio ?? '',
                'dob' => $request->dob ?? '',
                'exequatur_number' => $request->exequatur_number ?? ''
            ]);

            if (isset($request->profile_image)) {

                $oldimage = $data->profile_image;
                if ($oldimage != 'user.png') {

                    if (File::exists(public_path('/uploads/profile-imges/' . $oldimage)))
                        File::delete(public_path('/uploads/profile-imges/' . $oldimage));

                }
                $path = public_path('uploads/profile-imges');
                $uploadImg = $this->uploadDocuments($request->profile_image, $path);
                $data->profile_image = $uploadImg;
                $data->save();
            }
            // questions::where('doctor_id ',$data->id);

            if (!empty($request->question)) {
                questions::where('doctor_id', $data->id)->delete();
                foreach ($request->question as $value) {
                    $newQus = new questions;
                    $newQus->doctor_id = $data->id;
                    $newQus->question = $value;
                    $newQus->save();
                }
            }
            
            // dd($request->hospital);

            Avaliablity::where('doctor_id', $data->id)->delete();
            $availability = [];
            foreach($request->hospital as $hospital){
                for ($i = 1; $i <= 7; $i++) {
                    $availability[] = [
                        'week_day' => $i,
                        'start_time' => !empty($request->start_time[$hospital][$i]) ? $request->start_time[$hospital][$i] : '09:00',
                        'end_time' => !empty($request->end_time[$hospital][$i]) ? $request->end_time[$hospital][$i] : '17:00',
                        'status' => isset($request->weekday[$hospital][$i]) ? $request->weekday[$hospital][$i] : 0,
                        'hospital_id' => $hospital,
                        'doctor_id' => (!empty($request->id)) ? $request->id : $data->id
                    ];
                }
            }

            Avaliablity::upsert($availability, ['hospital_id', 'doctor_id', 'week_day'], ['start_time', 'end_time']);

            $data->speciality()->sync($request->input('speciality', []));
            $data->hospitals()->sync($request->input('hospital', []));
            $data->educations()->sync($request->input('education', []));

            $data->roles()->sync(3);

            $message = 'success';
            if($data)
            {
                if($request->id)
                {
                    return redirect()->route('doctor.index')->with('msg','Doctor is successfully updated');
                }
                else
                {
                    return redirect()->route('doctor.index')->with('msg','Doctor is successfully created');
                }
            }else
            {
                return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
            }

        } catch (\Exception $e) { 
            if ($data) {
                if ($request->id) {
                    return redirect()->route('doctor.index')->with('msg', 'Doctor is successfully updated');
                } else {
                    return redirect()->route('doctor.index')->with('msg', 'Doctor is successfully created');
                }
            } else {
                return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
            }
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

        $dt = new DateTime();
        $todayDate = $dt->format('Y-m-d');

        $result['title'] = "Doctor";
        $result['page_name'] = "Profile";
        $records = getenv('ADMIN_PAGE_LIMIT');
        if (isset($_GET['paginate'])) {
            $records = $request->paginate;
        }
        $result['doctor'] = User::findOrFail($id);

        $result['todayAppointment'] = Appointment::where('doctor_id', $result['doctor']->id)->whereDate('date', $todayDate)->get();
        $result['oldAppointment'] = Appointment::where('doctor_id', $result['doctor']->id)->whereDate('date', '<', $todayDate)->get();
        $result['upComingAppointment'] = Appointment::where('doctor_id', $result['doctor']->id)->whereDate('date', '>', $todayDate)->get();
        $result['startSubscription'] = SubscriptIonInventory::where('user_id', $result['doctor']->id)->first();

        $result['reviews'] = RatingReview::join('users', 'users.id', 'rating_reviews.patient_id')->select('users.name as user_name', 'rating_reviews.*')->where('rating_reviews.doctor_id', $id)->latest()->take(5)->get();

        $ids = $result['doctor']->speciality->pluck('id')->toArray();
        $result['questions'] = questions::whereIn('speciality_id', $ids)->get();

        $doc_aval = Avaliablity::where('doctor_id', $id);

        if (isset($request->doctor_availability)) {
            $doc_aval->where('hospital_id', $request->doctor_availability);
        }
        $result['doctor_avail'] = $doc_aval->get();



        if ($request->ajax() && $request->type == 'today') {


            $data = Appointment::select('appointments.id', 'appointments.doctor_id', 'appointments.date', 'appointments.consult_type', 'users.name as patient_name')
                ->join('users', 'appointments.patient_id', '=', 'users.id')
                ->where('doctor_id', $result['doctor']->id)
                ->whereDate('date', Carbon::now()->format('Y-m-d'))
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('time', function ($col) {
                    return Carbon::parse($col->time)->format('H:i:s');
                })
                ->editColumn('date', function ($row) {
                    return Carbon::parse($row->date)->format('d M Y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        if ($request->ajax() && $request->type == 'old') {

            $data = Appointment::select('appointments.id', 'appointments.doctor_id', 'appointments.date', 'appointments.consult_type', 'users.name as patient_name')
                ->join('users', 'appointments.patient_id', '=', 'users.id')
                ->where('doctor_id', $result['doctor']->id)
                ->whereDate('date', "<", Carbon::now()->format('Y-m-d'))
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('time', function ($col) {
                    return Carbon::parse($col->time)->format('H:i:s');
                })
                ->editColumn('date', function ($row) {
                    return Carbon::parse($row->date)->format('d M Y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        if ($request->ajax() && $request->type == 'upcoming') {

            $data = Appointment::select('appointments.id', 'appointments.doctor_id', 'appointments.date', 'appointments.consult_type', 'users.name as patient_name')
                ->join('users', 'appointments.patient_id', '=', 'users.id')
                ->where('doctor_id', $result['doctor']->id)
                ->whereDate('date', ">", Carbon::now()->format('Y-m-d'))
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('time', function ($col) {
                    return Carbon::parse($col->time)->format('H:i:s');
                })
                ->editColumn('date', function ($row) {
                    return Carbon::parse($row->date)->format('d M Y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $consult_type = !empty($result['doctor']->types_of_consultation) ? array_keys(json_decode($result['doctor']->types_of_consultation, true)) : [];
        $result['types_of_consultation'] = TypeOfConsultation::where('status', 1)->get();
        $result['doctor_consultation'] = TypeOfConsultation::whereIn('id', $consult_type)->get();

        $q = questions::where('doctor_id', $result['doctor']->id)->where('status', 1);
        if (isset($request->consult_type)) {
            $q->where('types_of_consultation_id', $request->consult_type);
        }

        $result['doctor_question'] = $q->get();


        return view('admin.doctor.view', $result);

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result['title'] = "Doctor";
        $result['page_name'] = "Edit";
        $result['speciality'] = Speciality::all()->pluck('name', 'id');
        $result['userData'] = User::findOrFail($id);
        $result['hospitals'] = Hospital::all()->pluck('name', 'id');
        // $result['education'] = Education::all()->pluck('name', 'id');
        // $data['userData']->Speciality = $data['userData']->Speciality->pluck('id')->toArray();
        if ($result['userData']) {
            return view('admin.doctor.create', $result);
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
        $request->validate([
            'type_of_consultation' => 'required',
            'price.*' => 'required|numeric'
        ]);

        try {
            $data = [];
            foreach ($request->type_of_consultation as $key => $value) {
                $data[$value] = $request->price[$value];
            }
            $user = User::where('id', $id)->update(['types_of_consultation' => json_encode($data)]);
            return redirect()->route('doctor.show', $id);
        } catch (\Exception $e) {
            return $e->getMessage();
        }


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
        if ($data) {
            return redirect()->route('doctor.index')->with('msg', 'Doctor is successfully Deleted');
        } else {
            return redirect()->route('doctor.index')->with('msg', 'Doctor unsuccessfully Deleted');
        }
    }
}