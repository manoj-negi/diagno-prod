<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Auth\UserResource;
use App\Mail\LoginMail;
use App\Models\Avaliablity;
use App\Models\Education;
use App\Models\Hospital;
use App\Models\MailTemplate;
use App\Models\Speciality;
use App\Models\User;
use Auth;
use App\Models\HospitalDoctor;
use App\Models\Appointment;
use Illuminate\Support\Facades\Hash;
use App\Helper\ResponseBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function geoLocation(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'address' => 'required',
            ]);

            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }
            
            $data = $this->lookForPoints($request->address);
            if(isset($data['address_components']) && !empty($data['address_components'])){
                return ResponseBuilder::successMessage('Success',  $this->success,$data);
            } 
            return ResponseBuilder::error('Something went wrong!', $this->badRequest);
        } catch (Exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }


	public function hospitalSignUp(Request $request)
	{
        try {
            $validator = Validator::make($request->all(), [
                'name'     			 => 'required',
                'email'     		 => 'required|email',
                'password'  		 => 'required|min:8',
                // 'hospital_category'  => 'required',
                'state'  			 => 'required',
                'city'  			 => 'required',


            ]);
            if ($validator->fails()) {   
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
            }


                $user = User::create([
                	'name'      => $request->name,
                    'email'     => $request->email,
                    'password'  => Hash::make($request->password),
                    // 'hospital_category' => $request->hospital_category,
                    // 'state' => $request->state,
                    // 'city' => $request->city,
                    'address' => $request->address ?? '',
                    'is_hospital' => true,

                ]);
                $user->roles()->sync(4);
            return ResponseBuilder::success($this->response, 'Registered Successfully!');   
        }
        catch (exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }




    public function patientSignUp(Request $request)
    {
       
         try {
            $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['required', 'email', 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', 'unique:users,email,' . $request->id],
            'number' => 'required|numeric|digits_between:10,12',
            'address' => 'required',
            'status' => 'required', 
            'dob' => 'required|date_format:Y-m-d'


            ]);
            if ($validator->fails()) {   
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
            }

                $user = User::create([
                   'name' => $request->name,
                   'email' => $request->email,
                   'number' => $request->number,
                   'dob' => $request->dob,
                   'address' => $request->address,
                   'status' => $request->status
                ]);

                if (isset($request->profile_image)) {

            $oldimage = $user->profile_image;
            if ($oldimage != 'user.png') {

                if (File::exists(public_path('/uploads/profile-imges/' . $oldimage)))
                    File::delete(public_path('/uploads/profile-imges/' . $oldimage));
                }

                $path = public_path('uploads/profile-imges');
                $uploadImg = $this->uploadDocuments($request->profile_image, $path);
                $user->profile_image = $uploadImg;
                $user->save();
            }
                $user->roles()->sync(2);
            return ResponseBuilder::success($this->response, 'Registered Successfully!');   
        }
        catch (exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    
}


public function hospitalDoctorSignUp(Request $request)
{
    try {
            $validator = Validator::make($request->all(), [
               'hospital_id' => 'required',
               'doctor_name' => 'required',
               'doctor_age' => 'required',
               'doctor_category' => 'required',
            ]);
            if ($validator->fails()) {   
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
            }

                $check = HospitalDoctor::where('hospital_id',$request->hospital_id)->where('doctor_name',$request->doctor_name)->first();

                if ($check) {
                    return ResponseBuilder::error('Record already exists', $this->badRequest);  
                }

                $user = HospitalDoctor::create([
                   'hospital_id' => $request->hospital_id,
                   'doctor_name' => $request->doctor_name,
                   'doctor_age' => $request->doctor_age,
                   'doctor_category' => $request->doctor_category,

                ]);

                // $user->roles()->sync(2);
            return ResponseBuilder::success($this->response, 'Registered Successfully!');   
        }
        catch (exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
}


        public function createAppointment(Request $request)
        {
            try {
                $validator = Validator::make($request->all(), [
                //    'doctor_id' => 'required',
                   'patient_id' => 'required',
                   'hospital_id' => 'required',
                   'date' => 'required',
                   'time' => 'required',
                   'consult_type' => 'required',
                //    'is_consult_completed' => 'required',
                //    'status' => 'required',
                ]);
                if ($validator->fails()) {   
                    return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
                }
    
                    $user = Appointment::create([
                    //    'doctor_id' => $request->doctor_id,
                       'patient_id' => $request->patient_id,
                       'hospital_id' => $request->hospital_id,
                       'date' => $request->date,
                       'time' => $request->time,
                       'consult_type' => $request->consult_type,
                    //    'is_consult_completed' => $request->is_consult_completed,
                    //    'status' => $request->status,
    
                    ]);
    
                    // $user->roles()->sync(2);
                return ResponseBuilder::success($user, 'Created Appointment Successfully!');   
            }
            catch (exception $e) {
                return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
            }
        }


        public function deactivate(Request $request)
        {
            // Get the authenticated user using Laravel Passport
            $user = $request->user();
        
            if (!$user) {
                return response()->json([
                    'message' => 'User not authenticated.'
                ], 401);
            }
        
            // Set the status to 0
            $user->status = 0;
            $user->save();
        
            return response()->json([
                'message' => 'User deactivated successfully.'
            ], 200);
        }
        
}



	// $user = new User;
		        // $user->name = $request->name;
		        // $user->email = $request->email;
		        // $user->password = Hash::make($request->password);
		        // $user->hospital_category = $request->hospital_category;
		        // $user->state = $request->state;
		        // $user->city = $request->city;
		        // $user->address = $request->address;
		        // $user->save();

                