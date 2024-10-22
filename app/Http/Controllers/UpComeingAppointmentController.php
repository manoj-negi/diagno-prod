<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\AppointmentReport;
use App\Models\Hospital;
use App\Models\RatingReview;
use App\Models\Support;
use App\Models\LabTest;
use Illuminate\Support\Facades\Auth;
use DB;

use DateTime;



use Carbon\Carbon;
class UpComeingAppointmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        $data['users'] = User::all()->count();
        $data['doctors'] = User::where('is_approved','pending')->get()->count();
        $data['rejected'] = User::where('is_approved','rejected')->get()->count();
        $dt = new DateTime();
        $todayDate = $dt->format('Y-m-d');

        $todayAppointments = Appointment::whereDate('date', now())->latest();
        $oldAppointments = Appointment::whereDate('date', '<', $todayDate)->latest();
        $upComingAppointments = Appointment::whereDate('date', '>', $todayDate)->latest();


        $AppointmentsTotal = Appointment::query();
        $AppointmentsComplete = Appointment::where('status',true);
        $AppointmentsPending = Appointment::where('status',false);

        $user = Auth::user();

        $reviews = RatingReview::join('users', 'users.id', 'rating_reviews.patient_id')->select('users.name as user_name', 'rating_reviews.*')->latest()->take(10);
        $supports = Support::latest()->take(10);
        $data['testList']= LabTest::where('hospital_id',Auth::user()->id)->take(10)->latest()->get();
        if($user->roles->contains(4)){
            $todayAppointments->where('hospital_id',Auth::user()->id);
            $oldAppointments->where('hospital_id',Auth::user()->id);
            $upComingAppointments->where('hospital_id',Auth::user()->id);


            $AppointmentsTotal->where('hospital_id',Auth::user()->id);
            $AppointmentsComplete->where('hospital_id',Auth::user()->id);
            $AppointmentsPending->where('hospital_id',Auth::user()->id);

            $reviews->where('hospital_id',Auth::user()->id);
        }
        $data['AppointmentsTotal'] = $AppointmentsTotal->count();
        $data['AppointmentsComplete'] = $AppointmentsComplete->count();
        $data['AppointmentsPending'] = $AppointmentsPending->count();

        $data['todayAppointments'] = $todayAppointments->take(5)->get();
        $data['oldAppointments'] = $oldAppointments->take(5)->get();
        $data['upComingAppointments'] = $upComingAppointments->take(5)->get();
        $data['reviews'] = $reviews->get();
        $data['supports'] = $supports->get();
        return view('admin.upcomeingappointments.index',$data);
        
    }

      public function ShowUserlist()
      {

            $today = Carbon::now()->format('Y-m-d');
            $now = Carbon::now()->addMinutes(30)->format('H:i');
            // dd ($now);
            $notifications = Appointment::whereDate('date', $today)->get();

           foreach ($notifications as $key => $value) {
          
             if (isset($value->patient->number))
              {
                 // code...
             }
             if (isset($value->patient->email)) {
                return $value->patient->email;
             }
              
           }
            return $notifications;
        
    }

}
