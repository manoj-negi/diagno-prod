<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\LoginMail;
use Illuminate\Http\Request;
use Validator;
use ResponseBuilder;
use App\Http\Resources\UserResource;
use Hash;
use App\Models\hospitals;
use App\Models\Medical_symptoms;
use App\Models\Medicaldiseases;
use App\Models\Medicinemodel;
use App\Models\Mails as MailTemplete;
use Mail;

class AuthController extends Controller
{


//* Login *//
// public function login(Request $request)
// {
//     try {
//         $validator = Validator::make($request->all(), [
//             'email_number' => 'required|exists:users,email',

//             'otp' => 'required',
//         ]);

//         if ($validator->fails()) {
//             return ResponseBuilder::error($validator->errors()->first(), $this->serverError);
//         }
//         $user = User::getUserByMobileAndEmail($request->email_number);


//         if ($user) {
//             if ($user->otp != $request->otp) {
//                 return ResponseBuilder::error(__("Otp does not match"), $this->badRequest);
//             }
//             $token = $user->createToken('Token')->accessToken;
//             $data = $this->setAuthResponse($user);

//             return ResponseBuilder::successWithToken($token, $data, 'Login successfully', $this->success);

//         } else {
//             return ResponseBuilder::error(__("User not registered"), $this->badRequest);
//         }
//     } catch (Exception $e) {
//         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
//     }

// }



// public function setAuthResponse($user)
// {
//     $this->response->user = new UserResource($user);
//     return $this->response->user;
// }

// //* //Register api *//

// public function register(Request $request)
// {
//     try {
//         $validator = Validator::make($request->all(), [
//             'name' => 'required',
//             'email' => 'email|unique:users,email',
//             'number' => 'required|Integer|unique:users,number|digits:10',

//         ]);

//         if ($request->role == 2) {
//             $validator = Validator::make($request->all(), [
//                 'name' => 'required',
//                 'email' => 'email|unique:users,email',
//                 'number' => 'required|Integer|unique:users,number|digits:10',
//                 'age' => 'required'

//             ]);
//         }
//         if ($request->role == 3) {
//             $validator = Validator::make($request->all(), [
//                 'name' => 'required',
//                 'email' => 'email|unique:users,email',
//                 'number' => 'required|Integer|unique:users,number|digits:10',

//             ]);
//         }


//         if ($validator->fails()) {
//             return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
//         }

//         $parameters = $request->all();
//         extract($parameters);

//         // $otp = '1234'; //$this->generateOtp(4);
//         $user = User::where('email', $email)->first();
//         // $sendMail = false;
//         if ($user) {
//             //
//             if ($user->status) {
//                 return ResponseBuilder::error(__("User Already Exist with this Email ID."), $this->badRequest);
//             } else {
//                 //
//                 $user = User::where('email', $request->email_number)->orWhere('number', $request->email_number)->update([
//                     'name' => $name,
//                     // 'password'  => Hash::make($password),
//                     'status' => '0',
//                     // 'otp'       => $otp
//                 ]);

//                 $sendMail = true;
//             }
//         } else {
//             $user = User::create([
//                 'name' => $name,

//                 'email' => $email,
//                 'number' => $number,
//                 'status' => '0',

//             ]);

//             if (!empty($user)) {
//                 $user->roles()->sync($role);
//             }
//             $sendMail = true;

//         }


//         $this->response->email_number = $email;

//         return ResponseBuilder::successMessage('Registered Successfully', $this->success);

//     } catch (exception $e) {
//         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
//     }
// }
// //* //Login with number api *//
// public function sendLoginOtp(Request $request)
// {
//     try {
//         $validator = Validator::make($request->all(), [
//             'email_number' => 'required',

//         ]);

//         if ($validator->fails()) {



//             return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);

//         }

//         $user = User::where('email', $request->email_number)->orWhere('number', $request->email_number)->first();

//         if (!$user) {
//             return ResponseBuilder::error(__("User not registered"), $this->badRequest);
//         }

//         $otp = random_int(1000, 9999);
//         $user->otp = $otp; //$this->generateOtp(4);
//         $user->save();

//         if ($user->email == $request->email_number) {
//             $getMail = MailTemplete::where('mail_cat', 'login')->first();

//             $arr1 = array('{otp}');
//             $arr2 = array($otp);


//             $msg = $getMail->message;
//             $msg = $getMail->url('images/mebel_logo.png');
//             $msg = str_replace($arr1, $arr2, $msg);

//             $config = [
//                 'from_email' => env('MAIL_FROM_ADDRESS'),
//                 'name' => env('MAIL_FROM_NAME'),
//                 'subject' => $getMail->subject,
//                 'message' => $msg,
//             ];

//             Mail::to($user->email)->send(new LoginMail($config));
//         }
//         if ($user->number == $request->email_number) {

//         }



//         $data = [
//             'email_number' => $request->email_number,
//             'otp' => $otp,
//         ];
//         return ResponseBuilder::success($data, 'OTP Sent Successfully', $this->success);


//     } catch (Exception $e) {
//         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
//     }
// }
// //  hospitls api

// public function hospital()
// {
//     try {

//         $data = hospitals::select('id', 'name')->get();

//         return ResponseBuilder::success($data, 'List of hospitals', $this->success);

//     } catch (\Exception $e) {

//         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
//     }
// }
// //    doctor specialty api

// public function doctorSpeciality()
// {
//     try {

//         $data = Doctorcategory::select('id', 'name')->get();

//         return ResponseBuilder::success($data, 'List of doctor speciality', $this->success);

//     } catch (\Exception $s) {

//         return ResponseBuilder::error(__($s->getMessage()), $this->serverError);
//     }
// }
// // Medicaldiseases api

// public function diseases()
// {
//     try {
//         $data = Medicaldiseases::select('id', 'name')->get();

//         return ResponseBuilder::success($data, 'List of doctor diseases', $this->success);
//     } catch (\Exception $d) {

//         return ResponseBuilder::error(__($d->getMessage()), $this->serverError);
//     }
// }
// // sy api

// public function medicalSymptoms()
// {
//     try {
//         $data = Medical_symptoms::select('id', 'name')->get();

//         return ResponseBuilder::success($data, 'List of doctor Medical_symptoms', $this->success);
//     } catch (\Exception $m) {

//         return ResponseBuilder::error(__($m->getMessage()), $this->serverError);
//     }
// }
// public function medicine()
// {
//     // $user = User::where('email', $email)->first();
//     try {
//         $data = Medicinemodel::with('medical')->get();

//         return ResponseBuilder::success($data, 'List of doctor  medicine', $this->success);
//     } catch (\Exception $i) {

//         return ResponseBuilder::error(__($i->getMessage()), $this->serverError);
//     }
// }
}