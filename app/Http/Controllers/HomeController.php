<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\AppointmentReport;
use App\Models\Hospital;
use App\Models\settings;
use App\Models\RatingReview;
use App\Models\Support;
use App\Models\LabTest;
use App\Models\Package;
use App\Models\DeleteAccountRequests;
use Illuminate\Support\Facades\Auth;
use App\Imports\Nirmaya;
use App\Models\LabTestName;

use DB;
use PDF;
use DateTime;
use SplFileObject;


use Carbon\Carbon;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

     public function UserAccountDelete(){
       return view('delete-user');
     }
     public function UserAccountDeletepost(Request $request){
        $request->validate([
            'email' => 'required|email' 
        ]);
        DeleteAccountRequests::create([
            'email' => $request->email,
        ]);
        return redirect()->back()->with('message', 'Request created successfully!');
     }
    //  public function importCsv(Request $request){
    //     // die("fvdsbvdskvdsjkvbdsvjkdsbvjkbjk");
    //     $request->validate([
    //         'file' => 'required|file|mimes:csv,txt',
    //         'lab' => 'required' 
    //     ]);

    //     $file = $request->file('file');
    //     $csvData = fopen($file, 'r');
    //     $firstRow = true;
    //     DB::beginTransaction();

    //     try {
    //         while (($row = fgetcsv($csvData)) !== FALSE) {
    //             if ($firstRow) {
    //                 $firstRow = false;
    //                 continue;
    //             }
    //             $result = LabTest::create([
    //                 'package_name' => $row[0] ?? '',
    //                 'amount' => $row[1] ?? '',
    //                 'lab_id' =>  $request->lab,
    //                 'description' => null,
    //                 'type'        => 'test',
    //             ]);
                
    //         }
    //         DB::commit();
    //         fclose($csvData);
    //         return redirect()->back()->with('msg', 'CSV file imported successfully.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         fclose($csvData);
    //         return redirect()->back()->with('msg', 'An error occurred during the import process.');
    //     }
    //  }

    public function importCsv(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:csv,txt',
        'lab' => 'required'
    ]);

    $file = $request->file('file');
    $csvData = fopen($file, 'r');
    $firstRow = true;
    DB::beginTransaction();

    try {
        while (($row = fgetcsv($csvData)) !== FALSE) {
            if ($firstRow) {
                // Skip the header row
                $firstRow = false;
                continue;
            }

            // Assuming the first column is test_name, the second is amount
            $testName = $row[0] ?? null; // Test name
            $amount = $row[1] ?? null;   // Amount

            if ($testName && $amount !== null) { // Check for valid test_name and amount
                // First, update or create the test in labs_tests
                $test = LabTest::updateOrCreate(
                    ['test_name' => $testName], // Unique field
                    [
                        // Only save additional fields if necessary
                    ]
                );

                // Now, create the association in lab_test_name
                LabTestName::updateOrCreate(
                    [
                        'lab_id' => $request->lab,   // Get lab_id from the request
                        'test_id' => $test->id,       // Use the ID from the labs_tests table
                    ],
                    [
                        'amount' => $amount,          // The amount to associate
                        'description' => null,        // Set as needed
                    ]
                );
            }
        }

        DB::commit();
        fclose($csvData);
        return redirect()->back()->with('msg', 'CSV file imported successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        fclose($csvData);
        
        // Log the exception message for debugging
        \Log::error('CSV Import Error: ' . $e->getMessage());
        
        return redirect()->back()->with('msg', 'An error occurred during the import process.');
    }
}

     public function importXlsx(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
        'lab' => 'required'
    ]);

    $file = $request->file('file');

    DB::beginTransaction();

    try {
        // Import the data using the import class
        Excel::import(new Nirmaya($request->lab), $file);

        DB::commit();
        return redirect()->back()->with('msg', 'XLSX file imported successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('msg', 'An error occurred during the import process: ' . $e->getMessage());
    }
}
     public function invoice($id){
        $pdfData['appointment'] = Appointment::where('id',$id)->first();
        $invoiceName = "invoice-00011$id.pdf";
        // return view('pdf/invoice', $pdfData);
        $pdf = PDF::loadView('pdf/invoice', $pdfData)->save(public_path("/uploads/pdf/$invoiceName"))->setPaper('a4', 'landscape');
        // return   $pdf->stream();
    

        return $pdf->download($invoiceName);
     }
    public function index()
    {
        $user = Auth::user();

        $data['AdminName'] = settings::where('key','name')->value('value') ?? 'Admin';

        $data['users'] = User::all()->count();
        $data['doctors'] = User::where('is_approved','pending')->get()->count();
        $data['rejected'] = User::where('is_approved','rejected')->get()->count();
        $dt = new DateTime();
        $todayDate = $dt->format('Y-m-d');

        $todayAppointments = Appointment::whereDate('date', now())->latest();
        $oldAppointments = Appointment::whereDate('date', '<', $todayDate)->latest();
        $upComingAppointments = Appointment::whereDate('date', '>', $todayDate)->latest();


        $AppointmentsTotal = Appointment::query();
        $AppointmentsComplete = Appointment::where('status','complete');
        $AppointmentsPending = Appointment::where('status','pending');
        $AppointmentsCollected = Appointment::where('status','collected');
        $AppointmentsArrived = Appointment::where('status','arrived');
        $AppointmentsReportUploaded = Appointment::where('status','report uploaded');
        $AppointmentsInprogress = Appointment::where('status','inprogress');

        $user = Auth::user();

        $reviews = RatingReview::join('users', 'users.id', 'rating_reviews.patient_id')->select('users.name as user_name', 'rating_reviews.*')->latest()->take(10);
        $supports = Support::latest()->take(10);
        // $data['testList']= LabTest::where('hospital_id',Auth::user()->id)->take(10)->latest()->get();
        if($user->roles->contains(4)){
            $todayAppointments->where('hospital_id',Auth::user()->id);
            $oldAppointments->where('hospital_id',Auth::user()->id);
            $upComingAppointments->where('hospital_id',Auth::user()->id);


            $AppointmentsTotal->where('hospital_id',Auth::user()->id);
            $AppointmentsComplete->where('hospital_id',Auth::user()->id);
            $AppointmentsPending->where('hospital_id',Auth::user()->id);
            $AppointmentsCollected->where('hospital_id',Auth::user()->id);
            $AppointmentsArrived->where('hospital_id',Auth::user()->id);
            $AppointmentsReportUploaded->where('hospital_id',Auth::user()->id);
            $AppointmentsInprogress->where('hospital_id',Auth::user()->id);

            $reviews->where('hospital_id',Auth::user()->id);
        }
        $data['AppointmentsTotal'] = $AppointmentsTotal->count();
        $data['AppointmentsComplete'] = $AppointmentsComplete->count();
        $data['AppointmentsPending'] = $AppointmentsPending->count();
        $data['AppointmentsCollected'] = $AppointmentsCollected->count();
        $data['AppointmentsArrived'] = $AppointmentsArrived->count();
        $data['AppointmentsReportUploaded'] = $AppointmentsReportUploaded->count();
        $data['AppointmentsInprogress'] = $AppointmentsInprogress->count();

        $data['todayAppointments'] = $todayAppointments->take(5)->get();
        $data['oldAppointments'] = $oldAppointments->take(5)->get();
        $data['upComingAppointments'] = $upComingAppointments->take(5)->get();
        // $data['reviews'] = $reviews->take(5)->get();
        $data['supports'] = $supports->take(5)->get();
        return view('admin.dashboard',$data);
        
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