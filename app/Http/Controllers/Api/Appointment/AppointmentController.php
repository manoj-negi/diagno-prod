<?php

namespace App\Http\Controllers\Api\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\Doctor\AppointmentAvailabilityResource;
use App\Http\Resources\Doctor\AppointmentTimeResource;
use App\Http\Resources\Doctor\DoctorAppointmentResource;
use App\Http\Resources\Doctor\AvaliablitiesCollection;
use App\Http\Resources\Patient\PatientDetailResource;
use App\Http\Resources\Patient\PatientAppointments;
use App\Models\Appointment;
use App\Models\AppointmentReport;
use App\Models\Avaliablity;
use App\Models\PatientQuestionAnswer;
use App\Models\PresetQuestionAnswer;
use App\Models\questions;
use App\Models\DoctorCheckup;
use App\Models\TypeOfConsultation;
use App\Models\ConsultDetail;
use App\Models\Disease;
use App\Models\ConsultSymptomAns;
use App\Models\Symptom;
use App\Models\User;
use App\Models\MailTemplate;
use App\Mail\LoginMail;
use Illuminate\Http\Request;
use Validator;
use File;
use Carbon\Carbon;
use stdClass;
use DateTime;
class AppointmentController extends Controller
{   
    public function __construct(){
        $this->response = new stdClass();
    }

    public function doctorTypeOfConsult(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required|numeric|exists:users,id,is_doctor,1'
            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $user = User::find($request->doctor_id);

            if (empty($user->types_of_consultation))
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);
            $userconsulttype = json_decode($user->types_of_consultation, true);
            $doctorconsult_ids = array_keys($userconsulttype);

            $data = TypeOfConsultation::whereIn('id', $doctorconsult_ids)->where('status', 1)->get()->map(function ($collect) use ($userconsulttype) {
                return ['id' => $collect->id, 'name' => $collect->consultation_name, 'price' => $userconsulttype[$collect->id]];
            });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function appointmentAvailability(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required|numeric|exists:users,id',
                'consultation_id' => 'required|numeric|exists:type_of_consultations,id',
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $patient = \Auth::user();
            $doctor = User::find($request->doctor_id);



            $doctorAvailability = Avaliablity::where('doctor_id', $request->doctor_id)->where('status', 1)->get();

            if ($doctorAvailability->count() == 0)
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $appointment = Appointment::create([
                'doctor_id' => $request->doctor_id,
                'patient_id' => $patient->id,
                'by_turn_time' => $doctor->by_turn_time,
                'doctor_consultation_type_id' => $request->consultation_id
            ]);

            if (!$appointment)
                return \ResponseBuilder::fail(trans('messages.SOMETHING'), $this->badRequest);

            $doctorAvailability[0]->appointment_id = $appointment->id;
            $doctorAvailability[0]->doctor_name = $doctor->name;

            $data = new AppointmentAvailabilityResource($doctorAvailability);

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function appointmentTimeAvaliablity(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required|numeric|exists:users,id',
                'week_day' => 'required|numeric|between:1,7',
                // 'date' => 'required|date_format:Y-m-d'
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $AvaliablityHospitals = Avaliablity::join('hospitals', 'hospitals.id', '=', 'avaliablities.hospital_id')->where('avaliablities.doctor_id', $request->doctor_id)->where('avaliablities.status', 1)->where('avaliablities.week_day', $request->week_day)->pluck('hospitals.name', 'hospitals.id');

            foreach ($AvaliablityHospitals as $key => $value) {
                $doctorTime = Avaliablity::where('doctor_id', $request->doctor_id)->where('status', 1)->where('hospital_id', $key)->where('week_day', $request->week_day)->first();

                $data[] = [
                    'hospital_id' => $key,
                    'hospital_name' => $value,
                    'day_avaliablity' => (!$doctorTime) ? [] : new AppointmentTimeResource($doctorTime),
                ];

            }
            // return $data;

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);



        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }


    // public function appointmentTime(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'doctor_id' => 'required|numeric|exists:users,id',
    //             // 'hospital_id' => 'required|numeric|exists:hospitals,id',
    //             // 'week_day' => 'required|numeric|between:1,7',
    //             'date' => 'required|date_format:Y-m-d',

    //         ]);

    //         if ($validator->fails())
    //             return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

    //         $doctorTime = Avaliablity::where('doctor_id', $request->doctor_id)->where('status', 1)->get();

    //         if (!$doctorTime)
    //             return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

    //         // $data = new AppointmentTimeResource($doctorTime);
    //         unset($doctorTime->id,$doctorTime->doctor_id,$doctorTime->created_at,$doctorTime->updated_at);

    //         return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $doctorTime);



    //     } catch (\Exception $e) {
    //         return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
    //     }
    // }


    public function saveAppointment(Request $request)
    {

        try {
            $user = \Auth::user();

            $validator = Validator::make($request->all(), [
                'appointment_step' => 'required|in:1,2',
                'appointment_id' => 'required|numeric|exists:appointments,id',
                'date' => ' required_if:appointment_step,1|date_format:Y-m-d',
                'time' => 'required_if:appointment_step,1|date_format:H:i',
                'hospital_id' => 'required_if:appointment_step,1|numeric|exists:hospitals,id'
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $appointment = Appointment::where('id', $request->appointment_id)->first();

            if ($request->appointment_step == 1) {

                $appointment->hospital_id = $request->hospital_id;
                $appointment->date = $request->date;
                $appointment->time = $request->time;
                $appointment->save();

                if ($appointment)
                    return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success);

                return \ResponseBuilder::fail(trans('messages.SOMETHING'), $this->badRequest);
            }
            if(!empty($appointment->pin_number)){
                $data = [
                    'name' => isset($appointment->doctor) ? (($appointment->doctor->name ?? '').' '.($appointment->doctor->last_name ?? '')) : '',
                    'appointment_id' => $appointment->id,
                    'pin_number' => $appointment->pin_number,
                    'appointment_date' => $appointment->date,
                    'appointment_time' => $appointment->time,
                    'hospital_name' => isset($appointment->hospital) ? $appointment->hospital->name : '',
                    'consult_type' => $appointment->consultType->consultation_name,
                    'consult_price' => json_decode($appointment->doctor->types_of_consultation, true)[$appointment->consultType->id],
                ];
                return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);
            }
            $token = $this->createToken();
            $pinNumber = rand(1000000, 9999999);
            $appointment->pin_number = $pinNumber;
            $appointment->status = 1;
            $appointment->token = $token;
            $appointment->save();

            $data = [
                'name' => isset($appointment->doctor) ? (($appointment->doctor->name ?? '').' '.($appointment->doctor->last_name ?? '')) : '',
                'appointment_id' => $appointment->id,
                'pin_number' => $pinNumber,
                'appointment_date' => $appointment->date,
                'appointment_time' => $appointment->time,
                'hospital_name' => isset($appointment->hospital) ? $appointment->hospital->name : '',
                'consult_type' => $appointment->consultType->consultation_name,
                'consult_price' => json_decode($appointment->doctor->types_of_consultation, true)[$appointment->consultType->id],
            ];

            $mailTemplate = MailTemplate::where('mail_key', 'thank-you')->first();
            // $tokenURL =  url('/consult-ques?token='.$token);
            $tokenURL =  'https://mebel.eoxyslive.com/consult-ques?token='.$token;
            // $tokenURL =  'https://dev-mebel.ewtlive.in/consult-ques?token='.$token;
            $array1 = ['{pin_number}', '{appointment_id}', '{appointment_date}', '{appointment_time}', '{hospital_name}','{url}','{patient_name}'];
            $array2 = [$pinNumber, $appointment->id, $appointment->date, $appointment->time, isset($appointment->hospital) ? $appointment->hospital->name : '',$tokenURL??'' , $appointment->patient->name ?? 'User'];

            $mailTemplate->content = str_replace($array1, $array2, $mailTemplate->content);

            $dataMessage = trans('notifications.create_appointment');
            $userId = $user->id;
            $title = 'Appointment booked';
            $appointmentID = $appointment->id;
            
             $this->createNotification($userId , $appointmentID , $title  , $dataMessage);

            $dataMessage1 = trans('notifications.create_appointment_doctor');
            $userId1 = $appointment->doctor_id;
            $title1 = 'New appointment booked';
            $appointmentID = $appointment->id;
            
             $this->createNotification($userId1 , $appointmentID , $title1  , $dataMessage1);
            \Mail::to($user->email)->send(new LoginMail($mailTemplate));

            /**Mail for  consult questions*/
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }


    public function upcomingAppointments()
    {
        try {
            $user = \Auth::user();
            $appointments = Appointment::where('patient_id', $user->id)
                ->where('status', 1)
                ->where('is_consult_completed',0)
                ->where('date', '>=', date('Y-m-d'))
                ->orderBy('date')
                ->get();

            if ($appointments->count() == 0)
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $data = $appointments->map(function ($collect) {
                return [
                    'appointment_id' => $collect->id,
                    'date' => $collect->date,
                    'time' => $collect->time,
                    'doctor_name' => $collect->doctor->name,
                    'consult_type' => $collect->consultType->consultation_name?? '',
                    'hospital_name' => $collect->hospital->name?? ''

                ];
            });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);


        } catch (\Exception $e) {

            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }
    public function patientsAppointments(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|numeric',
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $user = \Auth::user();

            $appointments = Appointment::where('patient_id', $request->patient_id)
                ->where('status', 1)
                ->whereNotNull('doctor_consultation_type_id')
                ->get();

            if ($appointments->count() == 0)
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $patient_details = ConsultDetail::where('patient_id', $request->patient_id)->where('status', 1)->orderBy('id', 'DESC')->get();
            $data = $patient_details->map(function ($collect) {

                $appointments = Appointment::where('id', $collect->appointment_id)->first();

                if (!empty($collect->disease_id)) {
                    $data = Disease::where('id', $collect->disease_id)->pluck('name')->toArray();
                    if(!empty($data))
                    $disease = $data[0];
                }
                return [
                    'appointment_id' => $appointments->id,
                    'date' => $appointments->date ?? '',
                    'time' => $appointments->time ?? '',
                    'doctor_name' => $appointments->doctor->name ?? '',
                    'disease' => $disease ?? '',
                    'doctor_comment' => $patient_details->doctor_opinion ?? '',

                ];
            });
            $patient  = User::where('id',$request->patient_id)->first();
            $from = new DateTime($patient->dob);
            $to   = new DateTime('today');
            $patientAge =$from->diff($to)->y;
           
            $this->response->patient_id = $patient->id ?? '';
            $this->response->patient_name = $patient->name ?? '';
            $this->response->patient_age = $patientAge ?? '';
            $this->response->data = $data;
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $this->response);


        } catch (\Exception $e) {

            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }
    public function appointmentDetails(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'appointment_id' => 'required|numeric|exists:appointments,id',
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $user = \Auth::user();

            $appointment = Appointment::where('id', $request->appointment_id)->where('status', 1)->first();

            if (!$appointment)
                return \ResponseBuilder::fail(trans('messages.SOMETHING'), $this->badRequest);

            $patient_details = ConsultDetail::where('appointment_id', $request->appointment_id)->where('status', 1)->orderBy('id', 'DESC')->first();

            $patient = User::find($appointment->patient_id);

            $dob = new \DateTime($patient->dob);
            $currentDate = new \DateTime(date('Y-m-d'));
            $age = $dob->diff($currentDate);

            $patient_login =
                [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'age' => $age->y,
                    'profile_image' => url('uploads/profile-imges') . '/' . $patient->profile_image,
                    'appointment_id' => $appointment->id
                ];
            $data =  !empty($patient_details) ? new PatientAppointments($patient_details) : '';
            $this->response->pateint = $patient_login;
            $this->response->details = $data;

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $this->response);

        } catch (\Exception $e) {

            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }


    // public function uploadReports(Request $request)
    // {

    //     try {

    //         $validator = Validator::make($request->all(), [
    //             'appointment_id' => 'required|numeric|exists:appointments,id',
    //             'report' => 'required',
    //             'report.*' => 'mimes:jpeg,png,jpg,pdf'
    //         ]);

    //         if ($validator->fails())
    //             return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

    //         $image = [];

    //         foreach ($request->file('report') as $report) {

    //             $filename = $this->uploadDocuments($report, public_path('/img/reports/'));

    //             $image[] = ['appointment_id' => $request->appointment_id, 'report_image' => $filename, 'report_title' => $request->report_title];
    //         }
    //         AppointmentReport::insert($image);

    //         $data = AppointmentReport::where('appointment_id', $request->appointment_id)->get()->map(function ($data) {
    //             return ['id' => $data['id'], 'report' =>url('/img/reports') . '/' . $data['report_image'],'name'=> $data['report_image'], 'report_title' => $data['report_title']];
    //         });

    //         return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);



    //     } catch (\Exception $e) {

    //         return \ResponseBuilder::fail($e->getMessage(), $this->badRequest);
    //     }

    // }


    public function uploadReports(Request $request)
    {


        try {

            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|numeric|exists:users,id',
                'report' => 'required|mimes:jpeg,png,jpg,pdf,docx',
                'report_title' => 'required',
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $filename = $this->uploadDocuments($request->report, public_path('/img/reports/'));

            $data = AppointmentReport::create([
                'patient_id' => $request->patient_id,
                'report_image' => $filename,
                'report_title' => $request->report_title
            ]);
            $data->report_image = url('/img/reports') . '/' . $data->report_image;

            $data = AppointmentReport::where('patient_id', $request->patient_id)->get()->map(function ($data) {
                return ['id' => $data['id'], 'report' => url('/img/reports') . '/' . $data['report_image'], 'name' => $data['report_image'], 'report_title' => $data['report_title']];
            });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);



        } catch (\Exception $e) {

            return \ResponseBuilder::fail($e->getMessage(), $this->badRequest);
        }

    }

    public function appointmentReports(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|numeric|exists:appointment_reports,patient_id',
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $data = AppointmentReport::where('patient_id', $request->patient_id)->get()->map(function ($data) {
                return ['id' => $data['id'], 'report' => url('/img/reports') . '/' . $data['report_image'], 'name' => $data['report_image'], 'report_title' => $data['report_title']];
            });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function DeleteReport(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'report_id' => 'required|numeric|exists:appointment_reports,id'
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $report = AppointmentReport::find($request->report_id);

            $check = $report->delete();

            if (File::exists(public_path('/img/reports/' . $report->report_image))) {
                File::delete(public_path('/img/reports/' . $report->report_image));
            }


            if ($check)
                return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success);

            return \ResponseBuilder::fail(trans('messages.SOMETHING'), $this->badRequest);


        } catch (\Exception $e) {

            return \ResponseBuilder::fail($e->getMessage(), $this->badRequest);
        }


    }

    public function DoctorAppointment(Request $request)
    {
        try {

            $user = \Auth::user();
            if (!$user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $appointments = Appointment::where('doctor_id', $user->id)->where('status', 1);

            if (isset($request->filter) && $request->filter == 'upcoming') {

                $appointments->whereDate('date', '>', date('Y-m-d'));

            } else {

                $appointments->whereDate('date', date('Y-m-d'));
            }

            $q = $appointments->get();
            if ($appointments->count() == 0)
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $data = new DoctorAppointmentResource($q);

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);
            // return \ResponseBuilder::successWithPaginate(trans('messages.SUCCESS'), $this->success, $q, $data);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }


    public function AppointmentQr(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'appointment_id' => 'required|numeric|exists:appointments,id,status,0'
            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $appointment = Appointment::find($request->appointment_id);

            \Auth::login($appointment->patient);

            $doctor = User::find($appointment->doctor->id);

            $doctorAvailability = Avaliablity::where('doctor_id', $doctor->id)->where('status', 1)->get();

            if ($doctorAvailability->count() == 0)
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $doctorAvailability[0]->appointment_id = $appointment->id;

            $data = new AppointmentAvailabilityResource($doctorAvailability);

            $token = auth()->user()->createToken('API Token')->accessToken;

            $user = new UserResource($appointment->patient);

            $this->response = [$user, $data];

            return \ResponseBuilder::successWithToken(trans('messages.SUCCESS'), $this->success, $token, $this->response);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function ReportQr(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'appointment_id' => 'required|numeric|exists:appointments,id,status,0'
            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $appointment = Appointment::find($request->appointment_id);

            \Auth::login($appointment->patient);

            $appointmentReport = AppointmentReport::where('appointment_id', $request->appointment_id)->orderBy('id', 'DESC')->get();

            $data = $appointmentReport->count() == 0 ? [] : $appointmentReport->map(function ($collect) {
                return ['id' => $collect->id, 'report' => url('/img/reports') . '/' . $collect->report_image];
            });

            $token = auth()->user()->createToken('API Token')->accessToken;

            $user = new UserResource($appointment->patient);

            $this->response = [$user, $data];

            return \ResponseBuilder::successWithToken(trans('messages.SUCCESS'), $this->success, $token, $this->response);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }


    public function presetQuestion()
    {
        try {

            $data = questions::where(['status' => 1, 'doctor_id' => 0])->get()->map(function ($collect) {
                return ['id' => $collect->id, 'question' => $collect->question];
            });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }


    public function patientQuestionAnswer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'appointment_id' => 'required|numeric|exists:appointments,id'
            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $appointment = Appointment::find($request->appointment_id);

            $data = questions::where('doctor_id', $appointment->doctor_id)
            ->where('types_of_consultation_id', $appointment->doctor_consultation_type_id)->where('status', 1)->get()->map(function ($collect) {
                return ['id' => $collect->id, 'question' => $collect->question];
            });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function savePatientQuestionAnswer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token'        => 'required',
                'question_ids' => 'required|array',
                'symptom'      => 'array'
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $appointment = Appointment::where('token', $request->token)->first();
             if (!$appointment)
                return \ResponseBuilder::fail('Invaild Token',   $this->badRequest);

            // if (count($request->question_ids==0))
            //     return \ResponseBuilder::fail('All question ids required with answers.', $this->badRequest);

            $insert = [];
            foreach ($request->question_ids as $key => $value) {
                if(!empty($value))
                $insert[] = ['question_id' => $key, 'appointment_id' => $appointment->id, 'answer' => $value];
            }

            $ConsultSymptomAns = [];
            if($request->symptom){
                foreach ($request->symptom as $key => $value) {
                    $ConsultSymptomAns[] = ['appointment_id' => $appointment->id, 'result' => $value,'symptom_id' => $key];
                }
                ConsultSymptomAns::insert($ConsultSymptomAns);
            }
            PatientQuestionAnswer::insert($insert);
          
            // $appointment->token = null;
            // $appointment->save();

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success);
        } catch (\Exception $e) {
            return $e;
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function savePresetQuestionAnswer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|numeric|exists:users,id',
                'question_ids' => 'required|array',
                // 'answer'     => 'required|array'
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            // if(count($request->question_ids) !=count($request->answer))
            //    return \ResponseBuilder::fail('All question ids required with answers.', $this->badRequest);

            foreach ($request->question_ids as $key => $value) {

                $presetquest = questions::where('id', $key)->where('doctor_id', 0)->first();

                if (!$presetquest)
                    return \ResponseBuilder::fail($key . ' id is invalid.', $this->badRequest);

                PresetQuestionAnswer::updateOrCreate(['question_id' => $key, 'patient_id' => $request->patient_id], [
                    'answer' => $value
                ]);

            }

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success);
        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }


    }

    public function getPresetQuestionAnswer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'appointment_id' => 'required|numeric|exists:appointments,id',
            ]);
            $appointment = Appointment::where('id', $request->appointment_id)->first();
            $symptomData = [];
            if($appointment->doctor->speciality){
                foreach($appointment->doctor->speciality as $item){
                    foreach($item->symptoms as $val){
                            $symptomData[] = $val->id;
                    }
                }
            }
            $Symptom = Symptom::whereIn('id',$symptomData)->get();
            $getConsultSymtomsIds = ConsultSymptomAns::where('appointment_id',$request->appointment_id)->where('result',true)->pluck('symptom_id')->toArray();
            $data['symptoms'] = $Symptom->map(function ($collect) use  ($getConsultSymtomsIds) {
                return [
                'id' => $collect->id,
                'symptom_title' => $collect->name ?? '',
                'result' => in_array($collect->id,$getConsultSymtomsIds) ? 1 : 0
                ];
            });
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);
                $data['PresetQuestionAnswer'] = PatientQuestionAnswer::where('appointment_id',$request->appointment_id)->get()->map(function ($collect) {
                    return ['id' => $collect->id,
                     'question' => $collect->Questions->question ?? '',
                     'answer' => $collect->answer ?? ''
                    ];
                });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success,$data);
        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }


    }
    public function getDoctorCheckupList(Request $request)
    {
        try {
                $PresetQuestionAnswer = DoctorCheckup::all()->map(function ($collect) {
                    return ['id' => $collect->id,
                     'checkup_name' => $collect->checkup_name ?? '',
                    ];
                });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success,$PresetQuestionAnswer);
        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }


    }
    public function patientRegisterAppointment(Request $request)
    {
        try {
            $user = \Auth::user();
            if (!$user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|numeric|exists:users,id,is_doctor,0',
                'consultType_id' => 'required|numeric|exists:type_of_consultations,id'
            ]);
            $dt = Carbon::now();
           
        
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);


            $getAvaliablity =  Avaliablity::where('doctor_id',$user->id)
            ->where('week_day',$dt->dayOfWeek)
            ->where('status',true)
            ->first();
            $pinNumber = rand(1000000, 9999999);
            $appointment = Appointment::create([
                'doctor_id' => $user->id,
                'patient_id' => $request->patient_id,
                'hospital_id' => $getAvaliablity->hospital_id ?? '',
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
                'pin_number' => $pinNumber,
                'doctor_consultation_type_id' => $request->consultType_id,
                'status' => 1
            ]);

            if ($appointment)
                return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, ['appointment_id' => $appointment->id, 'pin_number' => $pinNumber]);
            else
                return \ResponseBuilder::fail(trans('messages.SOMETHING'), $this->badRequest);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }


}