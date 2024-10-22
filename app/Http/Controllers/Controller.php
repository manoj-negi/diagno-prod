<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\UserMeta;
use App\Models\Avaliablity;
use App\Models\Notification;
use App\Models\Appointment;
use App\Models\User;
use Exception;
use App\Mail\SendMail;
use Mail;
use stdClass;
use Illuminate\Http\Request;
use Validator;
use Salman\GeoCode\Services\GeoCode;


class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $serverError = 500;
    protected $badRequest = 400;
    protected $unauthorized = 401;
    protected $forbidden = 403;
    protected $notFound = 404;
    protected $success = 200;
    protected $noContent = 204;
    protected $partialContent = 206;
    protected $response;

    public function __construct()
    {
        $this->response = new stdClass();
    }
    static public function lookForPoints($address)
    {
        $getPoints = new GeoCode();
        return $getPoints->getLatAndLong($address); 
    }

    static public function isValidPayload(Request $request, $validSet)
    {
        $validator = Validator::make($request->all(), $validSet);

        if ($validator->fails()) {
            return $validator->errors()->first();
        }
    }
    
    public function uploadDocuments($files, $path)
    {
        $imageName = str_replace(' ', '', substr(time(), -5) . $files->getClientOriginalName());
        $files->move($path, $imageName);
        return $imageName;
    }
    
    public function createToken()
    {   
        function generate_string($input, $strength = 16) {
            $input_length = strlen($input);
            $random_string = '';
            for($i = 0; $i < $strength; $i++) {
                $random_character = $input[mt_rand(0, $input_length - 1)];
                $random_string .= $random_character;
            }
            return $random_string;
        }
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return generate_string($permitted_chars, 50);
    }
    public function getAvaliablityByDoctor($hospital_id, $user_id)
    {
        return Avaliablity::where('hospital_id', $hospital_id)->where('doctor_id', $user_id)->get();
    }

    public function createNotification($receiverID, $appointmentId = null , $title = null , $message = null )
    {
        $userName = !empty(User::getNameById($receiverID)) ? User::getNameById($receiverID) :'User';
        $appointment = Appointment::where('id',$appointmentId)->first();
        $arr1 = array('{user}','{app_id}','{doctorname}');
        $arr2 = array($userName->name,$appointmentId,$appointment->doctor->name ?? '');
        $message = str_replace($arr1, $arr2, $message);
          Notification::create([
                'user_id'          => $receiverID,
                'appointment_id'   => $appointmentId,
                'title'            => $title,
                'message'          => $message,
            ]);
        }



        public function SendMail($mailTo, $mailData, $basicInfo)
        {
            try {
                $message = $mailData->message;
                foreach($basicInfo as $key=> $info){
                    $message = str_replace($key, $info, $message);
                }
                $config = [
                    'from_email' => $mailData->mail_from,
                    // "reply_email" => $mailData->reply_email,
                    'subject' => $mailData->subject, 
                    'name' => $mailData->name,
                    'message' => $message,
                ];
                
                Mail::to($mailTo)->send(new SendMail($config));
                return ['status'=>true, 'message'=> 'Mail Sent'];
            } catch (Exception $e) {
                return ['status'=>false, 'message'=> $e->getMessage()];
            }
    
        }


        static public function MailCategories($category = '', $status = false)
    {
        # code...
        $categories = [
            'sign-up-admin'=>'SignUp Mail To Admin',
            'signup-otp'=>'Sign OTP',
            'resend-otp'=>'Resend OTP',
            'forgot-password' => 'Forget Password',
            'contact-us' => 'Contact Us',
            'new-order' => 'New Order',
            'subscription' => 'Subscription',
            
        ];
        if(isset($category) && !empty($category) && array_key_exists($category, $categories)) {
            if($status)
                return $categories[$category];
            else
                return [$category => $categories[$category]];
        } else {
            return $categories;
        }
    }





}