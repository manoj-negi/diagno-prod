<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Resources\Auth\UserResource;
use App\Mail\LoginMail;
use App\Models\Avaliablity;
use App\Models\Education;
use App\Models\Hospital;
use App\Models\MailTemplate;
use App\Models\Speciality;
use App\Models\User;
use App\Models\LabCities;
use App\Helper\ResponseBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
class AuthController extends Controller
{

    public function SignUp(Request $request)
    {
        try {

            $rules = [
                'user_type' => 'required|in:doctor,patient',
                'name' => 'required|string',
                'last_name' => 'string',
                'number' => 'required|numeric|digits_between:8,12|unique:users,number',
                'email' => 'required|email|unique:users,email',
                'insurance_number' => 'required_if:user_type,patient',
                'speciality' => 'required_if:user_type,doctor',
                'hospital' => 'required_if:user_type,doctor',
                'exequatur_number' => 'required_if:user_type,doctor|unique:users,exequatur_number',
                'marital_status' => 'nullable|in:married,unmarried',
                'address' => 'required_if:user_type,patient',
                'dob' => 'required_if:user_type,patient|date_format:Y-m-d',
                'emergency_contact' => 'nullable|numeric|digits_between:8,12',
                'cedula' => 'nullable|numeric|min:11',
            ];

            // 'education' => 'required_if:user_type,doctor',
            // 'age' => 'required_if:user_type,patient|numeric|max:150',
            // 'sex' => 'required_if:user_type,patient|in:Male,Female,Non binary',

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
                return ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);
            if ($request->user_type == 'doctor') {
                // $education = Education::where('status', 1)->get()->pluck('id')->toArray();
                // $reqEducation = explode(',', $request->education);

                $speciality = Speciality::where('status', 1)->get()->pluck('id')->toArray();
                $reqSpeciality = explode(',', $request->speciality);

                $hospital = Hospital::where('status', 1)->get()->pluck('id')->toArray();
                $reqHospital = explode(',', $request->hospital);

                // if (count(array_intersect($reqEducation, $education)) != count($reqEducation))
                //     return ResponseBuilder::fail('Education ' . trans('messages.INVALID_ID'), $this->badRequest);

                if (count(array_intersect($reqSpeciality, $speciality)) != count($reqSpeciality))
                    return ResponseBuilder::fail('Speciality ' . trans('messages.INVALID_ID'), $this->badRequest);

                if (count(array_intersect($reqHospital, $hospital)) != count($reqHospital))
                    return ResponseBuilder::fail('Hospital ' . trans('messages.INVALID_ID'), $this->badRequest);

                $doctor = User::create( 
                    [
                        'name' => $request->name,
                        'last_name' => $request->last_name,
                        'number' => $request->number,
                        'email' => $request->email,
                        'exequatur_number' => $request->exequatur_number,
                        'experience' => isset($request->experience) ? $request->experience : 0,
                        'is_doctor' => 1
                    ]
                );

                $doctor->roles()->sync(3);
                $doctor->speciality()->sync($reqSpeciality);
                // $doctor->educations()->sync($reqEducation);
                $doctor->hospitals()->sync($reqHospital);


                $availdata = [];

                foreach ($reqHospital as $key => $hospital_id) {

                    for ($i = 1; $i <= 7; $i++) {

                        $availdata[] = [
                            'doctor_id' => $doctor->id,
                            'hospital_id' => $hospital_id,
                            'week_day' => $i,
                        ];

                    }
                }

                Avaliablity::insert($availdata);

                $mailTemplate = MailTemplate::where('mail_key', 'signup')->first()->toArray();

                \Mail::to($doctor->email)->send(new LoginMail($mailTemplate));

                $data = ['email' => $doctor->email, 'id' => $doctor->id];

                if (!empty($doctor)) {
                    return ResponseBuilder::success(trans('messages.SIGNUP_SUCCESS'), $this->success, $data);
                } else {
                    return ResponseBuilder::fail(trans('messages.SOMETHING'), $this->badRequest);
                }

            }

            $patient = User::create(
                [
                    'name' => $request->name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'number' => $request->number,
                    'is_approved' => 'approved',
                    'dob' => $request->dob,
                    'address' => $request->address,
                    'marital_status' => isset($request->marital_status) ? $request->marital_status : 'unmarried',
                    'insurance_number' => $request->insurance_number,
                    'emergency_contact' => isset($request->emergency_contact) ? $request->emergency_contact : null,
                    'cedula' => isset($request->cedula) ? $request->cedula : null
                ]
            );

            // 'sex' => $request->sex,
            // 'age' => $request->age,

            $patient->roles()->sync(2);


            $mailTemplate = MailTemplate::where('mail_key', 'signup')->first()->toArray();



            \Mail::to($patient->email)->send(new LoginMail($mailTemplate));

            // return 'sas';

            $data = ['email' => $patient->email, 'id' => $patient->id];

            if (!empty($patient)) {
                return ResponseBuilder::success(trans('messages.SIGNUP_SUCCESS'), $this->success, $data);
            } else {
                return ResponseBuilder::fail(trans('messages.SOMETHING'), $this->badRequest);
            }
        } catch (\Exception $e) {
            ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function myprofile()
    {
        $data = new UserResource(Auth::user());
        return ResponseBuilder::success($data,'Profile');
    }
    public function updateProfile(Request $request)
    {  
         $validator = Validator::make($request->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'number' => 'required|digits:10',
            'address' => 'required',
            'dob' => 'required',
            'profile_image' => 'mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails())
          return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);


        $user = Auth::user();
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->number = $request->number;
        $user->address = $request->address;
        $user->dob = $request->dob;
        $user->is_profile = true;
        if(!empty($request->file('profile_image'))){
            $user->profile_image =   $this->uploadDocuments($request->file('profile_image'),'uploads/profile-imges');
        }
        $user->save();
        $data = new UserResource(Auth::user());
        return ResponseBuilder::success($data,'Profile');
    }
    public function updateCity(Request $request)
    {  
         $validator = Validator::make($request->all(), [
            'city_id' => 'required',
        ]);

        if ($validator->fails())
          return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);

        $LabCities = LabCities::where('city',$request->city_id)->count();
        $data['available_serives'] = $LabCities > 0 ? true : false;
        
        return ResponseBuilder::success($data,'sucess');
    }

    public function sendLoginOtp(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'email_number' => 'required'
        ]);

        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
        }
        $email_number = $request->email_number;
        if (!preg_match("/^91/", $email_number)) {
            $email_number = '91' . $email_number;
        }
        
        $q = User::where(function ($query) use ($request) {
            $query->where('number', $request->email_number);
        });
        $user = $q->first();

       
        $otp = rand(1000, 9999);
        $currentTime = time();

        if (!$user) {
            
            $user = User::create([
                'email' => null,
                'number' => $request->email_number, 
                'is_patient' => true,
                'otp' => $otp,
                'otp_expire' => time() + 30, 
            ]);

            // Assign role (if needed)
            $user->roles()->sync(2);
        } else  {
            if ($user->otp_expire < $currentTime) {
                $user->otp = $otp;
                $user->otp_expire = $currentTime + 30; // OTP expires in 3 minutes
                $user->save();
            } else {
                // If OTP hasn't expired, return a message to reuse the current OTP
                return ResponseBuilder::error(trans('messages.OTP_STILL_VALID'), $this->badRequest);
            }
        }
        $this->sendOtpThroughMsg91($email_number, $otp);

        $data = ['email' => $user->email,'otp' => $otp]; // Optionally, remove OTP in production for security
        return ResponseBuilder::success($data, trans('messages.OTP_SUCCESS'));

    } catch (\Exception $e) {
        return ResponseBuilder::error($e->getMessage(), $this->serverError);
    }
}
private function sendOtpThroughMsg91($mobile_number, $otp)
{
    $authkey = "425054Aag3ulsUGD67065ebdP1"; 
    $template_id = "67065b76d6fc05131e19c933"; 

    $curl = curl_init();

    // Set cURL options to make a POST request to MSG91 API
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://control.msg91.com/api/v5/flow",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            "template_id" => $template_id,
            "short_url" => "0", // No short URL needed
            "recipients" => [
                [
                    "mobiles" => $mobile_number, // The user's mobile number
                    "var1" => $otp // The OTP you generated
                ]
            ]
        ]),
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authkey: $authkey", // Your MSG91 API key
            "content-type: application/json"
        ],
    ]);

    // Execute cURL request
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        // Log the error or handle it as needed
        \Log::error("MSG91 Error: " . $err);
    } else {
        // Optional: Log the response for debugging
        \Log::info("MSG91 Response: " . $response);
    }
}

    // public function sendLoginOtp(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'email_number' => 'required'
    //         ]);

    //         if ($validator->fails())
    //             return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);

    //         $q = User::where(function ($query) use ($request) {
    //             $query->where('number', $request->email_number);
    //         });
    //         $user = $q->first();
    //         $otp = '1234';

    //         if (!$user){
    //             $user = User::create([
    //                 'email' => null,
    //                 'number' => $request->email_number,
    //                 'is_patient' => true,
    //                 'otp' => $otp,
    //                 'otp_expire' => time() + 180,
    //             ]);
    //             $user->roles()->sync(2);
    //             $data = ['email' => $user->email, 'otp' => $otp];
    //             return ResponseBuilder::success($data,trans('messages.OTP_SUCCESS'));
    //         }

    //         if ($user->status != 1)
    //             return ResponseBuilder::error(trans('messages.ACCOUNT_CLOSE'), $this->badRequest);

    //         $user->otp = $otp;
    //         $user->otp_expire = time() + 180;
    //         $user->save();
    //         // $mailTemplate = MailTemplate::where('mail_key', 'login')->first()->toArray();
    //         // $mailTemplate['content'] = str_replace('{otp}', $otp, $mailTemplate['content']);
    //         // \Mail::to($user->email)->send(new LoginMail($mailTemplate));
    //         $data = ['email' => $user->email, 'otp' => $otp];
    //         return ResponseBuilder::success($data,trans('messages.OTP_SUCCESS'));


    //     } catch (\Exception $e) {
    //         return ResponseBuilder::error($e->getMessage(), $this->serverError);
    //     }

    // }
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email_number' => 'required',
                'otp' => 'required|numeric|digits:4'
            ]);

            if ($validator->fails())
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);


            $user = User::where(function ($query) use ($request) {
                $query->where('number', $request->email_number);
            })->first();

            if (!$user)
           {

              echo 'one';
                return ResponseBuilder::error(trans('messages.INVALID_CREDENTIAL'), $this->badRequest);
           }

            if ($user->otp != $request->otp){
            echo 'two';

                return ResponseBuilder::error(trans('messages.OTP_INVALID'), $this->badRequest);
            }
            if ($user->otp_expire < time()){
            echo 'three';

                return ResponseBuilder::error(trans('messages.OTP_EXPIRE'), $this->badRequest);
           }
            Auth::login($user);

            if (isset($request->fcm_token))
                $user->fcm_token = $request->fcm_token;
           
            $user->otp = null;
            $user->otp_expire = null;
            $user->save();

            $token = auth()->user()->createToken('API Token')->accessToken;

            $data = new UserResource($user);

            return ResponseBuilder::successWithToken(  $token, $data, trans('messages.LOGIN_SUCCESS'), $this->success);


        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), $this->serverError);
        }

    }

    public function user(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|numeric|exists:users,id'
            ]);
            if ($validator->fails())
                return ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $user = User::find($request->id);

            $data = new UserResource($user);
            return ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }


}