<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Resources\Patient\PatientAppointmentCollection;
use App\Http\Resources\PatientAppointmentResource;
use App\Http\Resources\AppointmentBillCollection;
use App\Http\Resources\AppointmentBillResource;
use App\Http\Resources\AppointmentReportCollection;
use App\Http\Resources\AppointmentReportResource;
use App\Http\Resources\HospitalCategoryCollection;
use App\Http\Resources\HospitalCategoryResource;
use App\Http\Resources\HospitalCollection;
use App\Http\Resources\LabTestCollection;
use App\Http\Resources\LabTestResource;
use App\Http\Resources\BookingCollection;
use App\Http\Resources\BookingResource;
use App\Http\Resources\HospitalResource;
use App\Http\Resources\ReviewCollection;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\PatientCollection;
use App\Http\Resources\PatientResource;
use App\Http\Resources\PatientBillCollection;
use App\Http\Resources\PatientBillResource;
use App\Http\Resources\FaqCollection;
use App\Http\Resources\FaqResource;
use App\Http\Resources\HospitalDoctorCollection;
use App\Http\Resources\HospitalDoctorResource;
use App\Http\Resources\CityCollection;
use App\Http\Resources\CityResource;
use App\Http\Resources\StateCollection;
use App\Http\Resources\StateResource;
use App\Http\Resources\SliderCollection;
use App\Http\Resources\SliderResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\Auth\UserResource;
use App\Models\Cart;
use App\Models\CartItem;

use App\Mail\LoginMail;
use App\Models\Avaliablity;
use App\Models\State;
use App\Models\Education;
use App\Models\Hospital;
use App\Models\Faq;
use App\Models\Slider;
use App\Models\PatientsAddess;
use App\Models\Role;
use App\Models\LabTest;
use App\Models\City;
use App\Models\Support;
use App\Models\settings;
use App\Models\Plan;
use App\Models\RatingReview;
use App\Models\MailTemplate;
use App\Models\Speciality;
use App\Models\User;
use App\Models\PatientReport;
use App\Models\HospitalDoctor;
use App\Models\Appointment;
use App\Models\AppointmentReport;
use App\Models\AppointmentBill;
use App\Models\AppointmentReferEaring;
use App\Models\HospitalCategory;
use App\Models\AppointmentPackages;
use App\Models\DoctorRecommended;
use App\Models\Testimonial;
use App\Models\Offer;
use Illuminate\Support\Facades\Hash;
use App\Helper\ResponseBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
// use Auth;
use App\Models\PatientFamily;
use DB;
use PDF;
class AppointmentController extends Controller
{

    public function addmember(Request $request)
    {

        if(Auth::guard('api')->check()) {   
            $user = Auth::guard('api')->user();
        } else {
            return ResponseBuilder::error("User not found", $this->unauthorized);
        }

        
        $validator = Validator::make($request->all(), [
             'name' => 'required',
              'patient_type' => 'required',
            ]);
            if ($validator->fails()) {   
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
            }

        try {
                $users = PatientFamily::updateOrCreate([
                    'id'=>$request->id,
                ],[
                  'patient_id' => $user->id ?? '', 
                   'patient_type' => $request->patient_type ?? '', 
                   'age' => $request->age ?? '', 
                   'gender' => $request->gender ?? '', 
                   'name' => $request->name ?? '',
                   'email' => $request->email ?? '',
                   'phone' => $request->phone ?? '',
                   'dob' => $request->dob ?? '',
                   'address' => $request->address ?? '',
                   'status' => $request->status ?? ''
                ]);

                
            return ResponseBuilder::success($users, 'Success');   
        }
        catch (exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }
    public function memberDelete(Request $request)
    {   
        if(Auth::guard('api')->check()) {   
             $user = Auth::guard('api')->user();
        } else {
            return ResponseBuilder::error("User not found", $this->unauthorized);
        }
        $validator = Validator::make($request->all(), [
            'member_id' => 'required',
        ]);
        if ($validator->fails()) {   
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
        }

        try {
            $data = PatientFamily::where('id',$request->member_id)->first();
            if(!$data){
                return ResponseBuilder::error('Invalid member id', $this->badRequest);  
            }
            $data->delete();
            return ResponseBuilder::success('', 'Member deleted successfully!');   
        }
        catch (exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }

    public function member(Request $request)
    {
        try {

            if(Auth::guard('api')->check()) {   
                $user = Auth::guard('api')->user();
            } else {
                return ResponseBuilder::error("User not found", $this->unauthorized);
            }
                // $user = Auth::id();
                $data = PatientFamily::where('patient_id',$user->id)->orderBy('id','desc')->get();

            return ResponseBuilder::success($data, 'Patient Family');   
        }
        catch (exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }

        public function createAppointment(Request $request)
        {
            try {
                if(Auth::guard('api')->check()) {
                    $user = Auth::guard('api')->user();
                } else {
                    return ResponseBuilder::error("User not found", $this->unauthorized);
                }
                // return $user;

                $validator = Validator::make($request->all(), [
                //    'patient_id' => 'required',
                   'hospital_id' => 'required',
                   'test_id' => 'required',
                   'date' => 'required',
                   'sex' => 'required',
                   'age' => 'required',
                   'time' => 'required',
                ]);
                if ($validator->fails()) {   
                    return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
                }
    
                // if(!empty($request->refer_code)){
                //     $UserRefer = User::where('refer_code',$request->refer_code)->first();
                //     if(empty($UserRefer)){
                //         return ResponseBuilder::error('Invalid Refer Code', $this->badRequest);  
                //     }
                // }
                $user = Appointment::create([
                    'patient_id' => $user->id,
                    'hospital_id' => $request->hospital_id,
                    'test_id' => $request->test_id,
                    'date' => $request->date,
                    'time' => $request->time,
                    // 'refer_code' => $request->refer_code ?? 'ADMIN',
                    
                    ]);

                    $data = [
                        'age' => $request->age,
                        'sex' => $request->sex,
                    ];
                     User::updateOrCreate(['id' => $user->patient_id], $data);
                     
                    $responseData = [
                        'user' => $user,
                        'User Details' => $data,
                    ];
                    
                    // $user->roles()->sync(2);
                return ResponseBuilder::success($responseData, 'Created Appointment Successfully!');   
            }
            catch (exception $e) {
                return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
            }
        }


        public function createAppointmentReport(Request $request)
        {
            try{
            $validator = Validator::make($request->all(), [
                'appointment_id' => 'required',
                'patient_id' => 'required',
                'report_title' => 'required',
                'report_image' => 'required',
             ]);
             if ($validator->fails()) {   
                 return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
             }
             $path = 'uploads/appointment';
             $oldFile= '';
             $data = AppointmentReport::create([
                'appointment_id' => $request->appointment_id,
                'patient_id' => $request->patient_id,
                'report_title' => $request->report_title,
                'report_image' => (!empty($request->file('report_image')) ? $this->uploadDocuments($request->file('report_image'), $path) : null),

             ]);

             return ResponseBuilder::success($data, 'Created Appointment Report Successfully!');   
            }
            catch (exception $e) {
                return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
            }
        }

        // patient appointment api 

        public function patientAppointment()
        {
             
        try {
            if(Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
            } else {
                return ResponseBuilder::error("User not found", $this->unauthorized);
            }
            // return $user;
          
            $patientAppointment = Appointment::where('patient_id',$user->id)->get();
           
            $this->response = new PatientAppointmentCollection($patientAppointment);
            
    
       
        return ResponseBuilder::success(  $this->response, ' Patient Appointment retrieved successfully');
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
        }

        // get hospital appointment 
        public function hospitalAppointment()
        {
             
        try {
            if(Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
            } else {
                return ResponseBuilder::error("User not found", $this->unauthorized);
            }
            // return $user;
          
            $hospitalAppointment = Appointment::where('hospital_id',$user->id)->get();
           
            $this->response = new PatientAppointmentCollection($hospitalAppointment);
            
    
       
        return ResponseBuilder::success(  $this->response, ' Hospital Appointment retrieved successfully');
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
        }

        // hospital appointment update
        public function appointmentUpdate(Request $request)
        {
              try{
                    $validator = Validator::make($request->all(), [
                        'id' => 'required',
                        'status'=>'required',
                    ]);
            
                    if ($validator->fails()) {
                        return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
                    }
            
                    if (Auth::guard('api')->check()) {
                        $user = Auth::guard('api')->user();
                  
                    } else {
                        return ResponseBuilder::error("User not found", $this->unauthorized);
                    }
                    $data = Appointment::where('id', $request->id)->first();
                    if(empty($data)){
                        return ResponseBuilder::error('Appointment not found', $this->badRequest);
                    }
                    $AppointmentReferEaring = AppointmentReferEaring::where('appointment_id',$request->id)->first();
                    if(empty($AppointmentReferEaring)){
                        
                        $userdata = User::where('refer_code',$data->refer_code)->whereDate('plan_expire_date','>',date('Y-m-d H:i:s'))->whereNotNull('plan_id')->first();
                        if(!empty($userdata) || $data->refer_code=='ADMIN'){
                            $getPlanCommission_percentage = 10;
                                if($data->refer_code == 'ADMIN'){
                                    $userdata = User::where('refer_code',$data->refer_code)->first();
                                    $settings = settings::where('key','admin_commission')->first();
                                    $getPlanCommission_percentage = $settings->value ?? 10;
                                }elseif(!empty($userdata->plan)){
                                    $getPlan = Plan::where('id',$userdata->plan_id)->first();
                                    $getPlanCommission_percentage = $getPlan->commission_percentage ?? 10;
                                }
                                $AppointmentBill = AppointmentBill::where('appointment_id',$data->id)->sum('amount');
                                if($AppointmentBill){
                                   $referAmount = ($AppointmentBill*$getPlanCommission_percentage)/100;
                                   AppointmentReferEaring::create([
                                    'appointment_id' => $request->id,
                                    'refer_code' =>$data->refer_code,
                                    'amount' => $referAmount,
                                   ]);
                                   $userdata->wallet = $userdata->wallet + $referAmount;
                                   $userdata->save();
                                }
                        }
                    }   
                    $data->status = $request->status;
                    $data->save();
        
                    return ResponseBuilder::success($data ,' Appointment Updated Successfully!',  $this->success);
        
                }    
                catch (\Exception $e) 
                {
            
                    return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
              }
        }

        // add Appointment bill api 
        
        public function appointmentBill(Request $request)
        {
            try {
                $validator = Validator::make($request->all(), [
                    'appointment_id'=>'required',
                    'amount' => 'required',
                    'title'=>'required',
                    'document_file' =>'required',
                 
                ]);
        
                if ($validator->fails()) {
                    return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
                }
        
                if (Auth::guard('api')->check()) {
                    $user = Auth::guard('api')->user();
              
                } else {
                    return ResponseBuilder::error("User not found", $this->unauthorized);
                }
                $path = 'uploads/appointment';
                $old_document_file = '';
                $data =[
    
                    'appointment_id' =>$request->appointment_id,
                    'title' => $request->title,
                    'amount'=>$request->amount,
                    'document_file'=> (!empty($request->file('document_file')) ? $this->uploadDocuments($request->file('document_file'), $path) : null),
                    
                    
                ];
             
                $document = AppointmentBill ::updateOrCreate(['id' => $request->id], $data);
    
                return ResponseBuilder::success($document ,' Appointment Bill Add Successfully!',  $this->success);
    
            }    
            catch (\Exception $e) 
            {
        
                return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
            }

        }

        // get appointment bill 

        public function appointmentBillList(Request $request)
        {
            try {
                $validator = Validator::make($request->all(), [
                    'appointment_id'=>'required',
                ]);
        
                if ($validator->fails()) {
                    return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
                }

                if(Auth::guard('api')->check()) {
                    $user = Auth::guard('api')->user();
                } else {
                    return ResponseBuilder::error("User not found", $this->unauthorized);
                }
                // return $user;
              
                $Appointment = AppointmentBill::where('appointment_id',$request->appointment_id)->get();
               
                $this->response = new AppointmentBillCollection($Appointment);
                
        
           
            return ResponseBuilder::success(  $this->response, '  Appointment bill retrieved successfully');
            } catch (\Exception $e) {
                return ResponseBuilder::error($e->getMessage(), 500);
            }
        }

        //  appointment report api

        public function appointmentReport(Request $request)
        {
            if (Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
            } else {
                return ResponseBuilder::error("User not found", $this->unauthorized);
            }
            try {
                $validator = Validator::make($request->all(), [
                    'appointment_id'=>'required',
                    'report_title' => 'required',
                    'report_image' =>'required',
                 
                ]);
        
                if ($validator->fails()) {
                    return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
                }
        
                $path = 'uploads/appointment';
                $old_report_image = '';
                $data =[
    
                    'appointment_id' =>$request->appointment_id,
                    'report_title' => $request->report_title,
                    'patient_id'=>$request->patient_id,
                    'report_image'=> (!empty($request->file('report_image')) ? $this->uploadDocuments($request->file('report_image'), $path) : null),
                    
                    
                ];
             
                $document = AppointmentReport ::updateOrCreate(['id' => $request->id], $data);
    
                return ResponseBuilder::success($document ,' Appointment Report Add Successfully!',  $this->success);
    
            }    
            catch (\Exception $e) 
            {
        
                return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
            }

        }

        // get appointment api 

        public function appointmentReportList(Request $request)
        {
            try {
                $validator = Validator::make($request->all(), [
                    'appointment_id'=>'required',
                ]);
        
                if ($validator->fails()) {
                    return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
                }

                if(Auth::guard('api')->check()) {
                    $user = Auth::guard('api')->user();
                } else {
                    return ResponseBuilder::error("User not found", $this->unauthorized);
                }
                // return $user;
              
                $Appointment = AppointmentReport::where('appointment_id',$request->appointment_id)->get();
               
                $this->response = new AppointmentReportCollection($Appointment);
                
        
           
            return ResponseBuilder::success(  $this->response, 'Appointment Report retrieved successfully');
            } catch (\Exception $e) {
                return ResponseBuilder::error($e->getMessage(), 500);
            }
        }


        // patient profile update 

    //     public function patientProfileUpdate(Request $request)
    // {
    //     {
    //         $validator = Validator::make($request->all(), [
    //             'name' => 'required',  
    //             'number'=>'required',
    //             'dob'=>'required',
    //             'email'=>'required|unique:users',

    //         ]);

    //     if ($validator->fails()) {   
    //         return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
    //     } 
        
    //     try{
    //         $userData = User::where('id',Auth::user()->id)->first();
    //         $path = 'uploads/patient';
    //         $oldLogo = '';
    //         $data = [
    //             'name' => $request->name ?? '',
    //             // 'number' => $request->number ?? '',
    //             'sex' => $request->gender ?? '',
    //             'address' => $request->address ?? '',
    //             'dob' => $request->dob ?? '',
    //             'email' => $request->email ?? '',

    //             'is_profile' => true,
    //         ];
            
    //         if(!empty($request->profile_image)){
               
    //             $data['profile_image'] = $this->uploadDocuments($request->file('profile_image'), $path) ;
    //         }
    //         $employee = User::updateOrCreate(['id' => $userData->id], $data);

    //         return ResponseBuilder::successMessage('Patient Profile Update Successfully!',  $this->success);

    // }    
    // catch (\Exception $e) 
    // {
    //     return $e;
    //     return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
    // }
    //     }
    // }
    public function patientProfileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'number' => 'required',
            'dob' => 'required',
            'email' => 'required',
        ]);
    
        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
        }
    
        try {
            $userData = User::where('id', Auth::user()->id)->first();
            $path = 'uploads/patient';
            $data = [
                'name' => $request->name ?? '',
                'sex' => $request->gender ?? '',
                'address' => $request->address ?? '',
                'dob' => $request->dob ?? '',
                'email' => $request->email ?? '',
                'is_profile' => true,
            ];
    
            if ($request->hasFile('profile_image')) {
                $data['profile_image'] = $this->uploadDocuments($request->file('profile_image'), $path);
            }
    
            $employee = User::updateOrCreate(['id' => $userData->id], $data);
    
            return ResponseBuilder::successMessage('Patient Profile Updated Successfully!', $this->success);
        } catch (\Exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }
    
    // public function uploadDocuments($file, $path)
    // {
    //     $filename = time() . '-' . $file->getClientOriginalName();
    //     $filepath = $file->storeAs($path, $filename, 's3');
    //     Storage::disk('s3')->setVisibility($filepath, 'public');
    //     return Storage::disk('s3')->url($filepath);
    // }
    
    
    // hospital profile update api

    public function hospitalProfileUpdate(Request $request)
    {
        
       
        {
            $validator = Validator::make($request->all(), [
           
            'name' => 'required',  
            'status'=>'required',
                     
            
        ]);

        if ($validator->fails()) {   
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
        } 
        
        if (!$request->id) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|unique:users',
            ]);

            if ($validator->fails()) {   
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            } 
        }
        


        try{
            $userData = User::where('id',Auth::user()->id)->first();
       

            

        $data = [
       
            'id' =>$request->id,
            'name' => $request->name,
            'password' => $request->password,
            'hospital_category' => $request->hospital_category,
            'status' => $request->status,
            'city_id' => $request->city_id,
            'email' => $request->email,
            'address' => $request->address,
           

        ];

        $employee = User::updateOrCreate(['id' => $userData->id], $data);


        return ResponseBuilder::successMessage('Hospital Profile Update Successfully!',  $this->success);

    }    
    catch (\Exception $e) 
    {
        return $e;
        return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
    }
        }
    }


    //get hospital profile update

    public function getHospitalProfileUpdate()
    {
        try {
            

            if(Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
            } else {
                return ResponseBuilder::error("User not found", $this->unauthorized);
            }
            // return $user;
            $hospital = User::where('id',$user->id)->first();
            $this->response = new HospitalResource($hospital);
            
        return ResponseBuilder::success($this->response, 'Hospital Profile retrieved successfully');
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }

    // get patient profile update api

    public function getPatientProfileUpdate()
    {
        try {
            if(Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
            } else {
                return ResponseBuilder::error("User not found", $this->unauthorized);
            }
            $patient = User::where('id',$user->id)->first();
            $this->response = new PatientResource($patient);
       
        return ResponseBuilder::success(  $this->response, 'Patient Profile retrieved successfully');
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }

    // add review api 

    public function addReview(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'hospital_id'=>'required',
                
             
            ]);
    
            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }
    
            if (Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
          
            } else {
                return ResponseBuilder::error("User not found", $this->unauthorized);
            }
          
            $data =[

                'hospital_id' =>$request->hospital_id,
                'ratings' => $request->ratings,
                'patient_id'=>$user->id,
                'review' => $request->review,
              
                
                
            ];
         
            $document = RatingReview ::updateOrCreate(['id' => $request->id], $data);

            return ResponseBuilder::success($document ,'Review Add Successfully!',  $this->success);

        }    
        catch (\Exception $e) 
        {
    
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }

    // get review list
    public function getReviewList(Request $request)
    {
        try {
            

            $validator = Validator::make($request->all(), [
                'hospital_id'=>'required',
                
             
            ]);
    
            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }

            if(Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
            } else {
                return ResponseBuilder::error("User not found", $this->unauthorized);
            }
            // return $user;
          
            $review = RatingReview::where('hospital_id',$request->hospital_id)->get();
           
            $this->response = new ReviewCollection($review);
            
    
       
        return ResponseBuilder::success(  $this->response, 'Review retrieved successfully');
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }
    

    // get hospital category api 
    public function hospitalCategoryList(Request $request)
    {
        try {
            $hospital = HospitalCategory::all();
            $this->response = new HospitalCategoryCollection($hospital);
            return ResponseBuilder::success($this->response, 'Hospital Category Retrieved Successfully');
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }


     // get category hospital api 
     public function categoryhospitalList(Request $request)
     {
         try {
            $validator = Validator::make($request->all(), [
                'hospital_category'=>'required',
            ]);
    
            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }
            if(Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
            } else {
                return ResponseBuilder::error("User not found", $this->unauthorized);
            }

            $hospital = User::where('hospital_category', $request->hospital_category)->get();
            
            $this->response = new HospitalCollection($hospital);

            return ResponseBuilder::success(  $this->response, 'Category hospital retrieved successfully');
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
     }

    //  get patient report api 

    public function patientReportList(Request $request)
    {


        try {
            if(Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
            } else {
                return ResponseBuilder::error("User not found", $this->unauthorized);
            }
    
            $validator = Validator::make($request->all(), [
                'patient_id'=>'required',
            ]);
            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }

            // return $user;
          
            $data = Appointment::where('patient_id',$user->id)->get();
            // return $data;
           
            $this->response = new AppointmentReportCollection($data);
            
    
       
        return ResponseBuilder::success($this->response, 'Patient report retrieved successfully');
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }

    // get patient bill
    public function patientBill(Request $request)
    {
        try {
            

            $validator = Validator::make($request->all(), [
                'patient_id'=>'required',
                
             
            ]);
    
            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }

            if(Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
            } else {
                return ResponseBuilder::error("User not found", $this->unauthorized);
            }
            // return $user;
          
            $data = Appointment::where('patient_id',$request->patient_id)->pluck('id')->toArray();
           $data = AppointmentReport::whereIn('appointment_id',$data)->get();
            $this->response = new PatientBillCollection($data);
            
    
       
        return ResponseBuilder::success($this->response , 'Patient bill retrieved successfully');
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }

    // get Faq list 

    public function getFaqList()
    {
       
        try {
            
            $faq = Faq::all();
            $this->response = new FaqCollection($faq);
            
    
       
        return ResponseBuilder::success(  $this->response, 'Faq retrieved successfully');
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }
    
    
    // get city list
    public function getCityList(Request $request)
    {
        // return $request;
        try {
            

            $validator = Validator::make($request->all(), [
                'state_id'=>'required',
                
             
            ]);
    
            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }

            if(Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
            } else {
                return ResponseBuilder::error("User not found", $this->unauthorized);
            }
            // return $user;
          
            $city = City::where('state_id',$request->state_id)->get();
           
            $this->response = new CityCollection($city);
            
    
       
        return ResponseBuilder::success(  $this->response, 'City retrieved successfully');
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }
    
    // get state list
    public function getStateList()
    {
       
        try {
            
            $state = State::all();
            $this->response = new StateCollection($state);
            
    
       
        return ResponseBuilder::success(  $this->response, 'State retrieved successfully');
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }
    // get site setting api

    public function getSiteSettingList()
    {
        $data = settings::select('key','value')->get();

        $data = $data->map(function ($setting) {
            if ($setting->key == 'logo' && $setting->value) {
                $setting->value = asset('assets/logo/' . $setting->value);
            }
            if ($setting->key == 'favicon' && $setting->value) {
                $setting->value = asset('assets/favicon/' . $setting->value);
            }
            return $setting;
        });
        return ResponseBuilder::success($data, 'success', $this->success);
        
    }

    // hospital list 

    public function hospitalList(Request $request)
    {
        // $query = User::where('is_hospital', true)->orderBy('id', 'desc');
        $query = Role::where('role', 'Lab')->first()->users();
        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('name', 'like', '%' . $searchTerm . '%');
                        //  ->orWhere('doctor_category', 'like', '%' . $searchTerm . '%');
            });
        }
        $hospitals = $query->get();

        $this->response = new HospitalCollection($hospitals);
        return ResponseBuilder::success($this->response, "Hospital List");
    }

    public function testList(Request $request)
    {
        // $query = User::where('is_hospital', true)->orderBy('id', 'desc');
        $query = LabTest::query();
        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('name', 'like', '%' . $searchTerm . '%');
                        //  ->orWhere('doctor_category', 'like', '%' . $searchTerm . '%');
            });
        }
        $hospitals = $query->get();

        // $this->response = new LabTestCollection($hospitals);
        // return ResponseBuilder::success($this->response, "Test List");
        return ResponseBuilder::success($hospitals, 'success', $this->success);
    }

    // submit contact api
    public function submitContact(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ], [
            'name.required' => 'Your Name is required!',
            'email.required' => 'Your email Id is required!',
            'phone.required' => 'Your phone number is required!',
            'message.required' => 'Subject is required!',
        ]);

        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
        }
        $parameters = $request->all();
        extract($parameters);

        try {
            
            //code...
            $enquiry = Support::create([
                'name' => ($name)??'',
                'email' => ($email)??'',
                'phone' => ($phone)??'',
                'subject' => ($subject)??'',
                'message' => ($message)??'',
            ]);
            if($enquiry) {

                // $mailData = MailTemplate::where('category', 'contact-us')->first();
                // $basicInfo = [
                //     '{name}' => ($name)??'',
                //     '{email}' => ($email)??'',
                //     '{phone}' => ($phone)??'',
                //     '{subject}' => ($subject)??'',
                //     '{message}' => ($message)??'',
                //     '{siteName}' => '',
                // ];
                // $this->SendMail($email, $mailData, $basicInfo);

                return ResponseBuilder::successMessage('Your Message has received. Thank you to contact us!', $this->success);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseBuilder::error(__($th->getMessage()), $this->serverError);
        }
        
        
    }


    public function myAddress(Request $request)
    {
        $UserAddresses = PatientsAddess::where('user_id',Auth::user()->id)->orderBy('id','desc')->get()->map(function($data){
            return [
                'id' => $data->id,
                'latitude' => $data->latitude,
                'longitude' => $data->longitude,
                'address' => $data->address,
                'house_no' => $data->house_no,
                'near_landmark' => $data->near_landmark,
                'area' => $data->area,
                'pin_code' => $data->pin_code,
                'state_id' => $data->state_id,
                'city_id' => $data->city,
                'address_type' => $data->address_type,
            ];
        });
    return ResponseBuilder::successMessage('Success',  $this->success , $UserAddresses);
    }

    public function deleteAddress($id)
    {   
        $UserAddresses = PatientsAddess::where('id',$id)->first();
        if(!$UserAddresses){
            return ResponseBuilder::error('Address not found', $this->badRequest);
        }
        $UserAddresses->delete();
        return ResponseBuilder::successMessage('Address removed successfully',  $this->success);
    }
    public function testimonials(Request $request)
    {   
        $validator = Validator::make($request->all(), [
           'type' => 'required|in:testimonials,doctor_recommended'
        ]);

        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
        }
        
        if($request->type == 'testimonials'){
            $data = Testimonial::where('status',true)->orderBy('id','desc')->get();
            $demoImage = '46491man_4140052.png';
        }
        if($request->type == 'doctor_recommended'){
            $data = DoctorRecommended::where('status',true)->orderBy('id','desc')->get();
            $demoImage = '46099medical_11500843.png';
        }
        $returnData = $data->map(function($data) use ($demoImage){
            return [
                'name' => $data->name ?? '',
                'image' => !empty($data->image) ? url('uploads/testimonial',$data->image) : url('uploads/testimonial',$demoImage),
                'designation' => $data->designation ?? '',
                'review' => $data->review ?? '',
                'rating' => $data->rating ?? '',
            ];
        });
        return ResponseBuilder::successMessage('success',  $this->success,$returnData);
    }
    public function createUpdateUserAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required',
            // 'address_type' => 'required',
            'house_no' => 'required',
            'near_landmark' => 'required',
            'area' => 'required',
            'pin_code' => 'required',
            'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
        }

        try {
            DB::beginTransaction();
            PatientsAddess::updateOrCreate([
                'id' => $request->id
            ],[
                'user_id' => Auth::user()->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $request->address,
                'house_no' => $request->house_no,
                'near_landmark' => $request->near_landmark,
                'area' => $request->area,
                'pin_code' => $request->pin_code,
                'city_id' => $request->city_id,
                'address_type' => $request->address_type,
            ]);
            $UserAddresses = PatientsAddess::where('user_id',Auth::user()->id)->orderBy('id','desc')->get()->map(function($data){
                return [
                    'id' => $data->id,
                    'latitude' => $data->latitude,
                    'longitude' => $data->longitude,
                    'address' => $data->address,
                    'house_no' => $data->house_no,
                    'near_landmark' => $data->near_landmark,
                    'area' => $data->area,
                    'pin_code' => $data->pin_code,
                    'city_id' => $data->city,
                    'address_type' => $data->address_type,
                ];
            });
            DB::commit();
            return ResponseBuilder::successMessage('Success',  $this->success , $UserAddresses);
        } catch (exception $e) {
            DB::rollBack();
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }



    }
//   public function addBooking(Request $request)
//   {
//     $validator = Validator::make($request->all(), [
//         'hospital_id'=>'required',
//         'date'=>'required',
//         'time'=>'required',
//         'patient_address'=>'required',
//         'report_hard_copy'=>'required|in:0,1',
//         'payment_mode'=>'required|in:online,cash',
//         'test_type'=>'required|in:test,profile,package',
//         'booking_amount'=>'required',
//         'packages'=>'required',
//     ]);

//     if ($validator->fails()) {
//         return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
//     }

//     try {
//         if (Auth::guard('api')->check()) {
//             $user = Auth::guard('api')->user();
      
//         } else {
//             return ResponseBuilder::error("User not found", $this->unauthorized);
//         }
//         $data = [
//             'test_id' =>$request->test_id,
//             'hospital_id' => $request->hospital_id,
//             'patient_id'=> $user->id,
//             'date' => $request->date,
//             'time' => $request->time,
//             'member_id' => $request->member_id,
//             'test_type' => $request->test_type,
//             'patient_address' => $request->patient_address,
//             'payment_mode' => $request->payment_mode,
//             'transaction_status' => 'pending',
//             'booking_amount' => $request->booking_amount,
//             'prescription_file' => (!empty($request->file('prescription_file')) ? $this->uploadDocuments($request->file('prescription_file'), 'uploads/booking') : null),
//         ];
//         $bookingData = Appointment::updateOrCreate(['id' => $request->id], $data);
//         $packageData = explode(",",$request->packages);
//         if(!empty($packageData)){
//             foreach($packageData as $item){
//                 AppointmentPackages::create([
//                     'appointment_id' => $bookingData->id,
//                     'package_id' => $item,
//                     'package_type' => $request->test_type,
//                 ]);
//             }
//         }
//         $bookingData = new BookingResource($bookingData);
//         return ResponseBuilder::success($bookingData ,'Booking Add Successfully!',  $this->success);

//     }    
//     catch (\Exception $e) 
//     {

//         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
//     }
//   } 
// ---------------------------latestbooking--------------
// public function addBooking(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'hospital_id' => 'required',
//         'date' => 'required',
//         'time' => 'required',
//         // 'status' => 'required',

//         // 'patient_address' => 'required',
//         // 'report_hard_copy' => 'required|in:0,1',
//         // 'payment_mode' => 'required|in:online,cash',
//         // 'test_type' => 'required|in:test,profile,package',
//         // 'booking_amount' => 'required',
//         // 'packages' => 'required',
//         // 'offer_id' => 'nullable|exists:offers,offer_id',
//     ]);

//     if ($validator->fails()) {
//         return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
//     }

//     try {
//         if (Auth::guard('api')->check()) {
//             $user = Auth::guard('api')->user();
//         } else {
//             return ResponseBuilder::error("User not found", $this->unauthorized);
//         }

//         $data = [
//             'test_id' => $request->test_id,
//             'hospital_id' => $request->hospital_id,
//             'patient_id' => $user->id,
//             'date' => $request->date,
//             'time' => $request->time,
//             'member_id' => $request->member_id,
//             'test_type' => $request->test_type,
//             'patient_address' => $request->patient_address,
//             'payment_mode' => $request->payment_mode,
//             'transaction_status' => 'pending',
//             'status' => 'pending',

//             'booking_amount' => $request->booking_amount,
//             'prescription_file' => (!empty($request->file('prescription_file')) ? $this->uploadDocuments($request->file('prescription_file'), 'uploads/booking') : null),
//         ];

//         $offerDetails = null;

//         if ($request->has('offer_id')) {
//             $offerId = $request->input('offer_id');
//             $appointment = new Appointment($data);
//             $offerDetails = $appointment->applyOffer($offerId);
//         } else {
//             $appointment = Appointment::updateOrCreate(['id' => $request->id], $data);
//         }

//         // $packageData = explode(",", $request->packages);
//         // if (!empty($packageData)) {
//         //     foreach ($packageData as $item) {
//         //         AppointmentPackages::create([
//         //             'appointment_id' => $appointment->id,
//         //             'package_id' => $item,
//         //             'package_type' => $request->test_type,
//         //         ]);
//         //     }
//         // }

//         // $appointment = new BookingResource($appointment);

//         $response = [
//             'message' => 'Booking added successfully!',
//             'data' => $appointment,
//         ];

//         if ($offerDetails) {
//             $response['offer'] = $offerDetails['offer'];
//             $response['discounted_amount'] = $offerDetails['discounted_amount'];
//         }

//         return ResponseBuilder::success($response, 'Booking Add Successfully!', $this->success);

//     } catch (\Exception $e) {
//         \Log::error('Error adding booking: ' . $e->getMessage());
//         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
//     }
// }

public function addToCart(Request $request)
{
    // Find or create the cart for the user
    $cart = Cart::firstOrCreate([
        'user_id' => auth()->id(),
        'status' => 'in_cart',
    ],[
        'user_id' => auth()->id(),
        'hospital_id' => $request->hospital_id,
        'date' => $request->date,
        'time' => $request->time,
        'payment_mode' => $request->payment_mode,
        'patient_address' => $request->patient_address,
        'booking_amount' => $request->booking_amount,
        'status' => 'in_cart',
    ]);

    // Add multiple tests to the cart (you can pass an array of test IDs from the request)
    foreach ($request->test_ids as $test_id) {
        CartItem::create([
            'cart_id' => $cart->id,
            'test_id' => $test_id,
            'test_type' => $request->test_type, // If test type varies, you can adjust this per item
        ]);
    }

    return response()->json(['success' => 'Tests added to cart successfully', 'cart' => $cart]);
}


public function getCart()
{
    // Fetch the cart with related items and their corresponding tests
    $cart = Cart::with('items.test')->where('user_id', auth()->id())->first();

    return response()->json(['cart' => $cart]);
}

public function addBooking(Request $request)
{
    $validator = Validator::make($request->all(), [
        'hospital_id' => 'required',
        'date' => 'required',
        'time' => 'required',
        // 'test_id' => 'required',
        'booking_amount' => 'required',
        'payment_mode' => 'required|in:online,cash', // Add payment mode validation
                // 'offer_id' => 'nullable|exists:offers,offer_id',

    ]);

    if ($validator->fails()) {
        return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
    }

    try {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        } else {
            return ResponseBuilder::error("User not found", $this->unauthorized);
        }

        $data = [
            'test_id' => $request->test_id,
            'hospital_id' => $request->hospital_id,
            'patient_id' => $user->id,
            'date' => $request->date,
            'time' => $request->time,
            'member_id' => $request->member_id,
            'test_type' => $request->test_type,
            'patient_address' => $request->patient_address,
            'payment_mode' => $request->payment_mode,
            'transaction_status' => 'pending', 
            'status' => 'pending',
            'booking_amount' => $request->booking_amount,
            'prescription_file' => (!empty($request->file('prescription_file')) ? $this->uploadDocuments($request->file('prescription_file'), 'uploads/booking') : null),
        ];

          // Offer Logic
          $offerDetails = null;

          if ($request->has('offer_id')) {
              $offerId = $request->input('offer_id');
              $offerDetails = (new Appointment($data))->applyOffer($offerId);
  
              // Adjust booking amount after offer is applied, if needed
              if ($offerDetails && isset($offerDetails['discounted_amount'])) {
                  $data['booking_amount'] = $offerDetails['discounted_amount'];
              }
          }
        if ($request->payment_mode == 'online') {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            // Create Razorpay order
            $orderData = [
                'receipt' => 'order_rcptid_'.time(), 
                'amount' => $request->booking_amount * 100, // Razorpay takes the amount in paise
                'currency' => 'INR'
            ];

            $razorpayOrder = $api->order->create($orderData);

            // Save the razorpay order ID in the database (optional)
            $data['transaction_id'] = $razorpayOrder['id'];
            $data['transaction_status'] = 'pending';

            // Create booking and send Razorpay order details to the frontend
            $appointment = Appointment::create($data);

            $response = [
                'message' => 'Booking created, proceed with payment.',
                'data' => $appointment,
                'transaction_id' => $razorpayOrder['id'],
                'razorpay_key' => env('RAZORPAY_KEY'), // Send the Razorpay key to the frontend
                'amount' => $request->booking_amount * 100, // Send the amount in paise
                'currency' => 'INR'
            ];

            return ResponseBuilder::success($response, 'Proceed to payment.', $this->success);
        }

        // If payment mode is cash, simply create the booking without Razorpay
        $appointment = Appointment::create($data);

        return ResponseBuilder::success([
            'message' => 'Booking added successfully with cash payment!',
            'data' => $appointment,
        ], 'Booking Add Successfully!', $this->success);

    } catch (\Exception $e) {
        \Log::error('Error adding booking: ' . $e->getMessage());
        return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
    }
}

//   public function completeBooking(Request $request){
//     $validator = Validator::make($request->all(), [
//         'booking_id'=>'required',
//         'transaction_status'=> 'required|in:sucess,failed,pending',
//         'transaction_id'=> 'required',
//     ]);

//     if ($validator->fails()) {
//         return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
//     }
//     $id = $request->booking_id;
//     $Appointment = Appointment::where('id',$request->booking_id)->first();
//     if(empty($Appointment)){
//         return ResponseBuilder::error('Booking not found', $this->badRequest);
//     }
//     $pdfData['appointment'] = Appointment::where('id',$id)->first();
//     $invoiceName = "invoice-000$id.pdf";
//     $pdf = PDF::loadView('pdf/invoice', $pdfData)->save(public_path("/uploads/pdf/$invoiceName"))->setPaper('a4', 'landscape');
//     $Appointment->transaction_status = $request->transaction_status;
//     $Appointment->invoice = '/uploads/pdf/'.$invoiceName;
//     $Appointment->transaction_id = $request->transaction_id;
//     $Appointment->save();
//     $bookingData = new BookingResource($Appointment);
    
//     return ResponseBuilder::success($bookingData ,'Booking updated successfully!',  $this->success);
// }
public function completeBooking(Request $request){
   
    // Validation with corrected 'transaction_status' rule
    $validator = Validator::make($request->all(), [
        'booking_id'=>'required',
        'transaction_status'=> 'required|in:success,failed,pending',  // Fix 'success'
        'transaction_id'=> 'required',
    ]);

    // Handle validation failure
    if ($validator->fails()) {
        return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
    }

    // Fetch appointment
    // $id = $request->booking_id;
    // $Appointment = Appointment::where('id', $id)->first();
    $id = $request->booking_id;
    $Appointment = Appointment::with('patient')->where('id', $id)->first();

    // dd($Appointment);
    // Check if appointment exists
    if (empty($Appointment)) {
        return ResponseBuilder::error('Booking not found', $this->badRequest);
    }

    // Wrap PDF generation and file saving in try-catch
    
   // echo "there we go";
        ini_set('max_execution_time', 300);
         $pdfData['appointment'] = $Appointment;
         $pdfData['patient'] = $Appointment->patient;
         // Reuse the fetched appointment
        $invoiceName = "invoice-000$id.pdf";
        


        // Generate and save PDF
        $pdf = PDF::loadView('pdf/invoice', $pdfData)
            ->save(public_path("/uploads/pdf/$invoiceName"))  // Ensure folder has write permissions
            ->setPaper('a4', 'landscape'); 
    

    // Update appointment data
    $Appointment->transaction_status = $request->transaction_status;
    $Appointment->invoice = '/uploads/pdf/'.$invoiceName;
    $Appointment->transaction_id = $request->transaction_id;
    $Appointment->save();

    // Return booking data
    $bookingData = new BookingResource($Appointment);
    
    return ResponseBuilder::success($bookingData, 'Booking updated successfully!', $this->success);
}

  // get booking 
//   public function getBooking(Request $request)
//   {
        
//     try {
//         $validator = Validator::make($request->all(), [
//             // 'hospital_id'=>'required',
//         ]);

//         if ($validator->fails()) {
//             return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
//         }

//         if(Auth::guard('api')->check()) {
//             $user = Auth::guard('api')->user();
//         } else {
//             return ResponseBuilder::error("User not found", $this->unauthorized);
//         }
//         // return $user;
      
//         $completeBooking = Appointment::where('patient_id',$user->id)->where('status','complete')->orderBy('id', 'desc')->get();
//         $pendingBookings = Appointment::where('patient_id',$user->id)->where('status','!=','complete')->orderBy('id', 'desc')->get();
//         $this->response->complete = new BookingCollection($completeBooking);
//         $this->response->pending = new BookingCollection($pendingBookings);

//     return ResponseBuilder::success(  $this->response, ' Booking retrieved successfully');
//     } catch (\Exception $e) {
//         return ResponseBuilder::error($e->getMessage(), 500);
//     }
//   }

  // get booking 
  public function getBooking(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'patient_id' => 'required|exists:users,id'
      ]);
  
      if ($validator->fails()) {
          return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
      }
  
      try {
          $patientId = $request->input('patient_id');
          
          // Check if the authenticated user exists
          if (Auth::guard('api')->check()) {
              $user = Auth::guard('api')->user();
          } else {
              return ResponseBuilder::error("User not found", $this->unauthorized);
          }
  
          // Fetch complete and pending bookings by patient_id
          $completeBooking = Appointment::where('patient_id', $patientId)
                                        ->where('status', 'complete')
                                        ->orderBy('id', 'desc')
                                        ->get();
          $pendingBookings = Appointment::where('patient_id', $patientId)
                                        ->where('status', '!=', 'complete')
                                        ->orderBy('id', 'desc')
                                        ->get();
  

                                        $this->response->complete = new BookingCollection($completeBooking);
                                                $this->response->pending = new BookingCollection($pendingBookings);
          // Merge the results into one collection
        //   $bookings = new BookingCollection($completeBooking->merge($pendingBookings));
  
          // Structure the response
        //   $response = [
        //       'message' => 'Booking retrieved successfully',
        //       'data' => $bookings,
        //   ];
  
    return ResponseBuilder::success(  $this->response, ' Booking retrieved successfully');
  
      } catch (\Exception $e) {
          \Log::error('Error fetching bookings: ' . $e->getMessage());
          return ResponseBuilder::error($e->getMessage(), $this->serverError);
      }
  }
  
  
 

    // get slider api 
    public function getslider()
    {
       
        try {
            
            $slider = Slider::all();
            $this->response = new SliderCollection($slider);
            
    
       
        return ResponseBuilder::success(  $this->response, 'Slider retrieved successfully');
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }



//apply offer

// public function applyOffer(Request $request)
// {
//     \Log::info('Request data:', $request->all());

//     $request->validate([
//         'offer_id' => 'required|exists:offers,offer_id',
//     ]);

//     $userId = Auth::id();

//     $pastAppointment = Appointment::where(function($query) use ($userId) {
//         $query->where('patient_id', $userId)
//               ->orWhere('member_id', $userId);
//     })
//     ->where('transaction_status', 'success')
//     ->exists();

    
//     if ($pastAppointment) {
//         return response()->json(['message' => 'Existing user cannot apply this offer.'], 403);
//     }

//     return response()->json([
//         'message' => 'Offer applied successfully.',
//         'offer' => $offers
//     ]);
// }
//   }
    
public function applyOffer(Request $request)
{
    \Log::info('Request data:', $request->all());

    try {
        $request->validate([
            'offer_id' => 'required|exists:offers,offer_id',
            'appointment_id' => 'required|exists:appointments,id'
        ]);

        $userId = Auth::id();

        $pastAppointment = Appointment::where(function($query) use ($userId) {
            $query->where('patient_id', $userId)
                  ->orWhere('member_id', $userId);
        })
        ->where('transaction_status', 'success')
        ->exists();

        if ($pastAppointment) {
            return response()->json(['message' => 'Existing user cannot apply this offer.'], 403);
        }

        $appointmentId = $request->input('appointment_id');
        $appointment = Appointment::find($appointmentId);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found.'], 404);
        }

        $offerId = $request->input('offer_id');
        $offer = Offer::find($offerId);

        if (!$offer) {
            return response()->json(['message' => 'Offer not found.'], 404);
        }

        $discountedAmount = $appointment->applyOffer($offerId);

        $appointment->refresh();

        return response()->json([
            'message' => 'Offer applied successfully.',
            'offer' => $offer,
            'appointment' => $appointment,
        ]);

    } catch (\Exception $e) {
        \Log::error('Error applying offer: ' . $e->getMessage());

        return response()->json(['message' => 'An error occurred while applying the offer.'], 500);
    }
}
// public function applyOffer(Request $request)
// {
//     \Log::info('Request data:', $request->all());

//     try {
//         $request->validate([
//             'offer_id' => 'required|exists:offers,offer_id',
//             'appointment_id' => 'required|exists:appointments,id'
//         ]);

//         $userId = Auth::id();

//         $pastAppointment = Appointment::where(function($query) use ($userId) {
//             $query->where('patient_id', $userId)
//                   ->orWhere('member_id', $userId);
//         })
//         ->where('transaction_status', 'success')
//         ->exists();

//         if ($pastAppointment) {
//             return response()->json(['message' => 'Existing user cannot apply this offer.'], 403);
//         }

//         $appointmentId = $request->input('appointment_id');
//         $appointment = Appointment::find($appointmentId);

//         if (!$appointment) {
//             return response()->json(['message' => 'Appointment not found.'], 404);
//         }

//         $offerId = $request->input('offer_id');
//         $offer = Offer::find($offerId);

//         if (!$offer) {
//             return response()->json(['message' => 'Offer not found.'], 404);
//         }

//         $result = $appointment->applyOffer($offerId);

//         return response()->json([
//             'message' => 'Offer applied successfully.',
//             'offer' => new OfferResource($result['offer']),
//             'appointment' => new BookingResource($result['appointment']),
//         ]);

//     } catch (\Exception $e) {
//         \Log::error('Error applying offer: ' . $e->getMessage());

//         return response()->json(['message' => 'An error occurred while applying the offer.'], 500);
//     }
// }

}