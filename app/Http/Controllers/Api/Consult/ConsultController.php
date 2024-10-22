<?php

namespace App\Http\Controllers\Api\Consult;

use App\Http\Controllers\Controller;
use App\Http\Resources\Patient\PatientDetailResource;
use App\Models\Appointment;
use App\Models\AppointmentReport;
use App\Models\CertificateReport;
use App\Models\ConsultDetail;
use App\Models\Disease;
use App\Models\MedicalTest;
use App\Models\Medication;
use App\Models\PatientQuestionAnswer;
use App\Models\ConsultCheckup;
use App\Models\Symptom;
use App\Models\DiseaseMedication;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use PDF;
use App\Models\MailTemplate;
use App\Mail\LoginMail;
use App\Models\ConsultaionSymptom;
use App\Helper\Helper;
use App\Models\questions;
use App\Models\Speciality;
use App\Models\TypeOfConsultation;
use App\Models\BloodTests;
class ConsultController extends Controller
{

    public function PinVerifyWithPatientDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [

                'appointment_id' => 'required|numeric|exists:appointments,id',
                'pin_number' => 'required|numeric|digits:7'
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $user = \Auth::user();

            $appointment = Appointment::where('id', $request->appointment_id)->where('status', 1)->first();

            if (!$appointment)
                return \ResponseBuilder::fail(trans('messages.SOMETHING'), $this->badRequest);

            if ((int) $appointment->doctor_id != $user->id)
                return \ResponseBuilder::fail(trans('messages.PIN_AUTHORIZED'), $this->badRequest);

            if ((int) $appointment->pin_number != $request->pin_number)
                return \ResponseBuilder::fail(trans('messages.INVALID_PIN'), $this->badRequest);


            $patient_details = ConsultDetail::where('patient_id', $appointment->patient_id)->where('status', 1)->orderBy('id', 'DESC')->get();

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

            $data = new PatientDetailResource($patient_details);

            $this->response->pateint = $patient_login;


            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $this->response);

        } catch (\Exception $e) {

            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function previousConsult()
    {
        try {
            $user = \Auth::user();
            if ($user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $consult = ConsultDetail::where('patient_id', $user->id)->orderBy('id', 'DESC')->get();

            if (!$consult->count())
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);


            $data = collect($consult)->map(function ($collect) {

                return [
                    'id' => $collect->appointment->id,
                    'date' => $collect->appointment->date,
                    'time' => $collect->appointment->time,
                    'disease' => $collect->disease->name ?? '',
                    'doctor_name' => $collect->doctor->name ?? '',
                    'reports' => ['report' => url('uploads/pdf') . '/' . ($collect->reports->report ?? ''), 'certificate' => url('uploads/pdf') . '/' . ($collect->reports->certificate ?? '')]
                ];

            });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }


    public function MakeConsult(Request $request)
    {
        try {

            $doctor = \Auth::user();

            if (!$doctor->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|numeric|exists:users,id,is_doctor,0',
                'appointment_id' => 'required|numeric|exists:appointments,id'
            ]);

            if ($validator->fails())
             return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);
             $ConsultDetail = ConsultDetail::where('appointment_id',$request->appointment_id)->count();

            if($ConsultDetail>0){
                return \ResponseBuilder::fail('Consult already created !', $this->badRequest);
            }
            
            $consult = ConsultDetail::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => $doctor->id,
                'appointment_id' => $request->appointment_id
            ]);

            $questionAnswer = PatientQuestionAnswer::where('appointment_id', $request->appointment_id)->get()->map(function ($collect) {
                return ['question_id' => $collect->question_id, 'question' => $collect->Questions->question, 'answer' => $collect->answer];
            });
            $Appointment = Appointment::where('id',$request->appointment_id)->first();
            if($Appointment){
                $Appointment->is_consult_completed =  true;
                $Appointment->save();
            }

            $dataMessage = trans('notifications.make_consult');
            $userId = $request->patient_id;
            $title = 'Consult created';
            $appointmentID = $request->appointment_id;
            $this->createNotification($userId , $appointmentID , $title  , $dataMessage);
            $userId1 = $doctor->id;
            $this->createNotification($userId1 , $appointmentID , $title  , $dataMessage);

            $data = ['consult_id' => $consult->id, 'question_answer' => $questionAnswer];

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function Disease(Request $request)
    {
        try {
               $validator = Validator::make($request->all(), [
                'appointment_id' => 'required|numeric|exists:appointments,id',]);
                
             $appointment = Appointment::find($request->appointment_id);
             if(!$appointment->doctor){
                return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $disease);
             }

             $user = $appointment->doctor;
             $disease = [];
              foreach($user->speciality as $item){
                  $diseaseData = Disease::whereIn('id', $item->diseases->pluck('id'))->get();
                     foreach($diseaseData as $val){
                        $disease[] = $val;
                     }
              }
           
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $disease);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function DiseaseSymptom(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'disease_id' => 'required|numeric|exists:diseases,id',
            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $disease = Disease::find($request->disease_id);
            $symptom = $disease->symptom;

            if (!$symptom->count())
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $this->response = $symptom->map(function ($collect) {
                return ['id' => $collect->id, 'name' => $collect->name];
            });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $this->response);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }
    public function getConsultQuesForPatientMail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

                $appointment = Appointment::where('token', $request->token)->first();
        
                $ConsultaionSymptom = ConsultaionSymptom::where('doctor_id',$appointment->doctor_id)->where('consultation_id',$appointment->doctor_consultation_type_id)->first();
                $symptomData = [];
                // return $appointment->doctor->speciality;
                if(!empty($ConsultaionSymptom)){
                    foreach($appointment->doctor->speciality as $item){
                      foreach($item->symptoms as $val){
                            $symptomData[] = $val;
                      }
                    }
                }
               $data['symptomData'] =  $symptomData;
            if (!$appointment)
                return \ResponseBuilder::fail('Invaild Token',  $this->badRequest);

                $data['question'] = questions::where('doctor_id', $appointment->doctor_id)
                ->where('types_of_consultation_id', $appointment->doctor_consultation_type_id)->where('status', 1)->get()->map(function ($collect) {
                    return ['id' => $collect->id, 'question' => $collect->question];
                });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function Symptom()
    {
        try {

            $symptom = Symptom::where('status', 1)->get();
            if (!$symptom->count())
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $this->response = $symptom->map(function ($collect) {
                return ['id' => $collect->id, 'name' => $collect->name];
            });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $this->response);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function AddDisease(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'disease_name' => 'required|string|unique:diseases,name'
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $disease = Disease::create([
                'name' => $request->disease_name
            ]);
            $this->response = ['id' => $disease->id, 'name' => $disease->name];
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $this->response);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function AddSymptom(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'symptom_name' => 'required|string|unique:symptoms,name'
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $symptom = Symptom::create([
                'name' => $request->symptom_name
            ]);

            $this->response = ['id' => $symptom->id, 'name' => $symptom->name];

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $this->response);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }
    public function BloodTests(Request $request)
    {
        try {
            $BloodTests = BloodTests::all();
             $BloodTests = $BloodTests->map(function ($collect)  {
                return [
                    'id' => $collect->id, 
                    'title' => $collect->test_name, 
                ];
            });
      
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $BloodTests);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }
    public function PrescribeMedicines(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'disease_id' => 'required|numeric|exists:diseases,id',
                'symptom_ids' => ['required', 'regex:/^([0-9]+\s*,\s*)*[0-9]+$/'],
                 'appointment_id' => 'required|numeric|exists:appointments,id',
                 'doctor_checkup' => 'array',
            ], [
                    'symptom_ids.regex' => 'The :attribute field must be id and a comma-separated list of words.',
                ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $symptoms = Symptom::where('status', 1)->pluck('id')->toArray();
            $reqSymptoms = explode(',', $request->symptom_ids);

            if (count(array_intersect($reqSymptoms, $symptoms)) != count($reqSymptoms))
                return \ResponseBuilder::fail('Symptom ' . trans('messages.INVALID_ID'), $this->badRequest);

            $disease = Disease::where('id', $request->disease_id)->where('status', 1)->first();

            $disease->symptom()->sync($reqSymptoms, false);
               
            $cunsultData  = ConsultDetail::where('appointment_id', $request->appointment_id)->update(['disease_id' => $request->disease_id, 'symptom_ids' => $request->symptom_ids,'doctor_opinion'=> $request->doctor_opinion]);
             $cunsultData  = ConsultDetail::where('appointment_id', $request->appointment_id)->first();
             
             if(!$cunsultData)
             return \ResponseBuilder::fail('Invaild appointment id', $this->badRequest);

            $medicines = $disease->medications->count() == 0 ? [] : $disease->medications->map(function ($collect) {
                return [
                    'id' => $collect->id,
                    'name' => $collect->name
                ];
            });
            $medicalTest = $disease->medicaltests->count() == 0 ? [] : $disease->medicaltests->map(function ($collect) {
                return [
                    'id' => $collect->id,
                    'name' => $collect->name,
                ];
            });

            $this->response = [
                'disease_id' => (int) $request->disease_id,
                'consult_id' => (int) $cunsultData->id,
                'medicines' => $medicines,
                'medical_test' => $medicalTest
            ];
            //Doctor checkup
            if(!empty($request->doctor_checkup)){
            $insert = [];
            foreach ($request->doctor_checkup as $key => $value) {
                if(!empty($value))
                $insert[] = ['consult_id' => $cunsultData->id,
                'checkup_id'  =>  $key, 
                'result'     =>  $value];
            }

            ConsultCheckup::insert($insert);
        }
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $this->response);


        } catch (\Exception $e) {
            return $e;
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }
    public function getPrescribeMedicinesAndTests(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                 'appointment_id' => 'required|numeric|exists:appointments,id',
            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            
            $cunsultData  = ConsultDetail::where('appointment_id', $request->appointment_id)->first();
             
             if(!$cunsultData)
             return \ResponseBuilder::fail('Invaild appointment id', $this->badRequest);
             $disease = Disease::where('id', $cunsultData->disease_id)->where('status', 1)->first();
            if($disease){
                $medicines = $disease->medications->count() == 0 ? [] : $disease->medications->map(function ($collect) {
                    return [
                        'id' => $collect->id,
                        'name' => $collect->name
                    ];
                });
                $medicalTest = $disease->medicaltests->count() == 0 ? [] : $disease->medicaltests->map(function ($collect) {
                    return [
                        'id' => $collect->id,
                        'name' => $collect->name,
                    ];
                });
           }
            $this->response = [
                'disease_id' => (int) $cunsultData->disease_id,
                'consult_id' => (int) $cunsultData->id,
                'medicines' => $medicines ?? [],
                'medical_test' => $medicalTest ?? []
            ];
           
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $this->response);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }
    public function Medicine()
    {
        try {

            $medicine = Medication::where('status', 1)->get();
            if (!$medicine->count())
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $this->response = $medicine->map(function ($collect) {
                return ['id' => $collect->id, 'name' => $collect->name];
            });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $this->response);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function AddMedicine(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:medications,name',
                'appointment_id' => 'numeric|exists:appointments,id',
            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $medication = Medication::create([
                'name' => $request->name
            ]);
            
            $cunsultData  = ConsultDetail::where('appointment_id', $request->appointment_id)->first();
            if($cunsultData){
                DiseaseMedication::create([
                    'disease_id'     => $cunsultData->disease_id ?? null,
                    'medication_id'  => $medication->id ?? null,
                ]);
            }

            $data = ['medicine_id' => $medication->id, 'medicine_name' => $medication->name];

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }


    public function MedicalTest()
    {
        try {

            $medicalTest = MedicalTest::where('status', 1)->get();
            if (!$medicalTest->count())
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $this->response = $medicalTest->map(function ($collect) {
                return ['id' => $collect->id, 'name' => $collect->name];
            });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $this->response);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function AddMedicalTest(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:medical_tests,name',
            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $medicalTest = MedicalTest::create([
                'name' => $request->name
            ]);

            $data = ['medical_test_id' => $medicalTest->id, 'medical_test_name' => $medicalTest->name];

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }
    public function generateCertificateAndMail(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'appointment_id' => 'required|numeric|exists:appointments,id',
                // 'medicine_ids' => ['required', 'regex:/^([0-9]+\s*,\s*)*[0-9]+$/'],
                // 'medicine_days' => ['required', 'regex:/^([0-9]+\s*,\s*)*[0-9]+$/'],
                // 'time' => 'required|array',
                'type' => 'required|in:email,certificate',
                'date' => ' required_if:type,certificate|date_format:Y-m-d',
                'days' => ' required_if:type,certificate|numeric',
            ]);
           
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);
                $consult = ConsultDetail::where('appointment_id', $request->appointment_id)->first();
                $appointmentData = Appointment::where('id', $request->appointment_id)->first();
                // return $consult;
                if(!$consult)
                return \ResponseBuilder::fail('Consult not found for this Appointment', $this->badRequest);


                $medicine_ids = explode(',', $consult->medication_ids);
                $medicineD = json_decode($consult->medicine_dosage,true);
                $medicinDaysArray = [];
                foreach($medicineD as $item){
                    $medicinDaysArray[] = $item['day'];
                }

                $medicine_days = $medicinDaysArray;
                $test_ids = explode(',', $consult->test_ids);

                $disease = Disease::find($consult->disease_id);
                $data = $medicineD;
                $medicineName = Medication::whereIn('id', $medicine_ids)->pluck('name', 'id')->toArray();
                $medicine = Helper::medicineTaken($data, $medicineName);
                $testName = MedicalTest::whereIn('id', $test_ids)->pluck('name')->toArray();
                $dob = new \DateTime($consult->patient->dob);
                $currentDate = new \DateTime(date('Y-m-d'));
                $age = $dob->diff($currentDate);

                $pdfData = [
                    'patient_name' => $consult->patient->name,
                    'patient_age' => $age->y,
                    'patient_sex' => $consult->patient->sex,
                    'consult_date' => date('d M Y', strtotime($consult->created_at)),
                    'medicines' => $medicine,
                    'tests' => implode(', ', $testName),
                    'doctor_name' => $consult->doctor->name ?? '',
                    'doctor_speciality' => implode(', ', $consult->doctor->speciality->pluck('name')->toArray()),
                    'patientid' => $consult->patient->id ?? '',
                    'doctorCheckups' => (Helper::consultDoctorCheckups($consult->id)),
                    'disease' => $consult->disease->name ?? '',
                    'doctorComment' => $consult->doctor_opinion ?? '',
                    'next_appointment' => date('d M Y', strtotime($consult->next_appointment_date)),
                ];
                // return  $pdfData;
                // $patient_name = $pdfData['patient_name'];   
               
                if($request->type=='certificate'){
                    $rand = rand(10, 99);
                    $certificateName = "patient{$consult->patient_id}_{$rand}_Certificate.pdf";
                    PDF::loadView('pdf/certificate', [
                        'patient_name' => $consult->patient->name,
                        'patient_id' => $consult->patient->id,
                        'patient_age' => $age->y,
                        'patient_sex' => $consult->patient->sex,
                        'consult_date' => date('d M Y', strtotime($consult->created_at)),
                        'doctor_name' => $consult->doctor->name,
                        'doctor_speciality' => implode(', ', $consult->doctor->speciality->pluck('name')->toArray())
                    ])->save(public_path("/uploads/pdf/$certificateName"));


                    $certificateReport = CertificateReport::where('consult_id',$consult->id)->first();
                    $certificateReport->certificate_valid_from = $request->date;
                    $certificateReport->certificate_valid_days = $request->days;
                    $certificateReport->save();
                    $responseData['cunsultCertificate'] = url('uploads/pdf/'.$certificateName);
                    return \ResponseBuilder::success('Certificate created', $this->success,$responseData);

                }
                $bloodTestIds = explode(',', $consult->blood_test_ids);
                $bloodTestId = BloodTests::whereIn('id', $bloodTestIds)->pluck('test_name')->toArray();

                $symptomData = explode(',', $consult->symptom_ids);
                $symptomData = Symptom::whereIn('id', $symptomData)->pluck('name')->toArray();

                $bloodTestIdData = '';
                foreach($bloodTestId as $item){
                    $bloodTestIdData .= $item .',';
                }

                $symtomsData = '';
                foreach($symptomData as $item){
                    $symtomsData .= $item .',';
                }
                $medicineTime = '';
                foreach($pdfData['medicines'] as $item){
                 $medicineTime .= '<div style=" padding-left: 5%; padding-right:5%; font-style: normal;
                      padding-top:10px;
                        font-size: 16px;
                        line-height: 22px;
                        text-transform: capitalize;
                        color: #4F537A;">
                        <p>
                            <span style="font-weight: 600; font-size: 18px;">'.$item['name'].' &nbsp;</span>:&nbsp; <span >'.$item['data'].'</span>
                        </p>
                        </div>';
                }

                $doctorCheckups = '';
                foreach($pdfData['doctorCheckups'] as $item){
                    $doctorCheckups .= ' <p>
                    '.$item["name"].' :
                    '.$item["result"].'
                    <br />
                </p>';
                }
                $reviewURl =   'https://mebel.eoxyslive.com/review-feedback?token='.$appointmentData->token ?? '';
                // http://localhost:3000/review-feedback
                $mailTemplate = MailTemplate::where('mail_key', 'consult-report')->first();
                if($mailTemplate && !empty($consult->patient->email)){
                    $array1 = ['{doctorspeciality}','{patientname}','{patientage}','{patientsex}','{consultdate}','{tests}','{medicineTime}','{doctorname}','{reviewurl}','{blood_test}','{symptoms}','{disease}','{doctorcomment}','{nextappointmentdate}','{doctorcheckups}'];

                    $array2 = [$pdfData['doctor_speciality']??'-', $pdfData['patient_name']??'-', $pdfData['patient_age']??'-', $pdfData['patient_sex']??'-',$pdfData['consult_date']??'-',$pdfData['tests']??'',$medicineTime ?? '-',$pdfData['doctor_name'] ?? '-' ,$reviewURl ?? '' ,$bloodTestIdData ?? '',$symtomsData ??'',$pdfData['disease'] ?? '',$pdfData['doctorComment'] ?? '',$pdfData['next_appointment'] ?? '',$doctorCheckups];
        
                    $mailTemplate->content = str_replace($array1, $array2, $mailTemplate->content);
                    \Mail::to($consult->patient->email)->send(new LoginMail($mailTemplate));

                }
                $mailTemplateCertificate = MailTemplate::where('mail_key', 'consult-certificate')->first();
                if($mailTemplateCertificate && !empty($consult->patient->email)){
                    $array5 = ['{doctorspeciality}','{patientname}','{patientage}','{patientsex}','{consultdate}','{patientid}','{doctorname}'];

                    $array7 = [$pdfData['doctor_speciality']??'-', $pdfData['patient_name']??'-', $pdfData['patient_age']??'-', $pdfData['patient_sex']??'-',$pdfData['consult_date']??'-',$pdfData['patientid']??'',$pdfData['doctor_name'] ?? '-'];
        
                    $mailTemplateCertificate->content = str_replace($array5, $array7, $mailTemplateCertificate->content);
                    \Mail::to($consult->patient->email)->send(new LoginMail($mailTemplateCertificate));
                }
         
                return \ResponseBuilder::success('Certificate and report send to mail', $this->success,);
                return \ResponseBuilder::fail(trans('messages.SOMETHING'), $this->badRequest);


        } catch (\Exception $e) {
                return $e;
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function CompleteConsult(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'appointment_id' => 'required|numeric|exists:appointments,id',
                'disease_id' => 'required|numeric|exists:diseases,id',
                'medicine_ids' => ['required', 'regex:/^([0-9]+\s*,\s*)*[0-9]+$/'],
                'medicine_days' => ['required', 'regex:/^([0-9]+\s*,\s*)*[0-9]+$/'],
                'blood_test_ids' => ['regex:/^([0-9]+\s*,\s*)*[0-9]+$/'],
                // 'time' => 'required|array',
                // 'days' => 'required|array',
                // 'test_ids' => ['required', 'regex:/^([0-9]+\s*,\s*)*[0-9]+$/'],
                // 'medicines_comment' => 'array',
                // 'next_appointment_date' => 'date_format:Y-m-d',
            ]);
            // return json_encode($request->time);
            // return gettype($request->time);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);
                $medicine_ids = explode(',', $request->medicine_ids);
                $medicine_days = explode(',', $request->medicine_days);
                $test_ids = explode(',', $request->test_ids);
               
                
                if (count($medicine_ids) != count($medicine_days))
                return \ResponseBuilder::fail('All medicines days is required.', $this->badRequest);
                
                if (count($medicine_ids) != count($request->time))
                return \ResponseBuilder::fail('All medicines time is required.', $this->badRequest);
                
                // if (count($medicine_ids) != count($request->week))
                //     return \ResponseBuilder::fail('All medicines weeks is required.', $this->badRequest);
                
                $disease = Disease::find($request->disease_id);
                $disease->medications()->sync($medicine_ids, false);
                $disease->medicaltests()->sync($test_ids, false);
                
                $data = [];
                $taken = [];
                foreach ($medicine_ids as $key => $value) {
                    $time = explode(',', $request->time[$key]);
                    $data[$value] = [
                        'day' => $medicine_days[$key],
                        'before' => $time[0] ?? '',
                        'after' => $time[1] ?? '',
                        // 'week' => $request->week[$key]
                    ];
                    $taken[$value] = 0;
                }
                
            $consult = ConsultDetail::where('appointment_id', $request->appointment_id)->first();
            $consult->medication_ids = $request->medicine_ids;
            $consult->medicine_dosage = json_encode($data);
            $consult->test_ids = $request->test_ids;
            $consult->medicine_taken = json_encode($taken);
            $consult->medicines_comment = json_encode($request->medicines_comment);
            $consult->blood_test_ids = $request->blood_test_ids;
            $consult->blood_tests_other_data = $request->blood_tests_other_data;
            $consult->medical_tests_other_data = $request->medical_tests_other_data;
            $consult->next_appointment_date = $request->next_appointment_date;
            $consult->status = 1;
            $consult->save();

            $medicineName = Medication::whereIn('id', $medicine_ids)->pluck('name', 'id')->toArray();
            $bloodTestIds = explode(',', $request->blood_test_ids);
            $bloodTestId = BloodTests::whereIn('id', $bloodTestIds)->pluck('test_name')->toArray();
            $symptomData = explode(',', $consult->symptom_ids);
            $symptomData = Symptom::whereIn('id', $symptomData)->pluck('name')->toArray();
            $medicine = Helper::medicineTaken($data, $medicineName);
            
            $testName = MedicalTest::whereIn('id', $test_ids)->pluck('name')->toArray();

            $dob = new \DateTime($consult->patient->dob);
            $currentDate = new \DateTime(date('Y-m-d'));
            $age = $dob->diff($currentDate);
     
            $pdfData = [
                'patient_name' => $consult->patient->name,
                'patient_age' => $age->y ?? '-',
                'patient_sex' => $consult->patient->sex,
                'next_appointment' => date('d M Y', strtotime($consult->next_appointment_date)),
                'consult_date' => date('d M Y', strtotime($consult->created_at)),
                'medicines' => $medicine,
                'tests' => implode(', ', $testName),
                'doctor_name' => $consult->doctor->name,
                'blood_tests' => $bloodTestId ?? [],
                'symptom' =>  $symptomData ?? [],
                'doctor_speciality' => implode(', ', $consult->doctor->speciality->pluck('name')->toArray()),
                'doctorCheckups' => (Helper::consultDoctorCheckups($consult->id)),
                'disease' => $consult->disease->name ?? '',
                'doctorComment' => $consult->doctor_opinion ?? ''
            ];
           
            
            $rand = rand(10, 99);
            
            $reportName = "patient{$consult->patient_id}_{$rand}_Report.pdf";
            PDF::loadView('pdf/prescribe', $pdfData)->save(public_path("/uploads/pdf/$reportName"));
            
            $certificateName = "patient{$consult->patient_id}_{$rand}_Certificate.pdf";
            PDF::loadView('pdf/certificate', [
                'patient_name' => $consult->patient->name,
                'patient_id' => $consult->patient->id,
                'patient_age' => $consult->patient->age,
                'patient_sex' => $consult->patient->sex,
                'consult_date' => date('d M Y', strtotime($consult->created_at)),
                'doctor_name' => $consult->doctor->name,
                'doctor_speciality' => implode(', ', $consult->doctor->speciality->pluck('name')->toArray())
                ])->save(public_path("/uploads/pdf/$certificateName"));
                CertificateReport::where('consult_id',$consult->id)->delete();
                $certificateReport = CertificateReport::create([
                    'appointment_id' => $consult->appointment_id,
                    'consult_id' => $consult->id,
                    'patient_id' => $consult->patient_id,
                    'report' => $reportName,
                    'certificate' => $certificateName
                ]); 
                
            $responseData['cunsultID'] = $consult->id;
            $responseData['cunsultReport'] = url('uploads/pdf/'.$reportName);
            $responseData['cunsultCertificate'] = url('uploads/pdf/'.$certificateName);


            $dataMessage = trans('notifications.completed_consult');
            $userId = $consult->patient->id;
            $title = 'Consult completed';
            $appointmentID = $consult->appointment_id;
            $this->createNotification($userId , $appointmentID , $title  , $dataMessage);
            
            $dataMessage1 = trans('notifications.completed_consult_doctor');
            $userId1 = $consult->doctor_id;
            $this->createNotification($userId1 , $appointmentID , $title  , $dataMessage1);

             if ($certificateReport)
                return \ResponseBuilder::success('Consultation Completed', $this->success,$responseData);
            return \ResponseBuilder::fail(trans('messages.SOMETHING'), $this->badRequest);


        } catch (\Exception $e) {
                return $e;
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }


    }


    public function MakeConsultion(Request $request)
    {
        try {

            $doctor = \Auth::user();

            if (!$doctor->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            // return $request->all();
            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required|numeric|exists:users,id,is_doctor,1',
                'types_of_consultation' => 'required',
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            User::where('id', $doctor->id)->update(['types_of_consultation' => $request->types_of_consultation]);
            // $user->save();
            // return $user;

            $data = [];

            foreach (json_decode($request->types_of_consultation) as $key => $value) {
                $data[] = [
                    'id' => $key,
                    'price' => $value
                ];
            }

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function getConsultation()
    {
        try {
            $user = \Auth::guard('api')->user();
            $types_of_consultation = json_decode($user->types_of_consultation);

            $data = TypeOfConsultation::where('status', 1)->get()->map(function ($collect) use($types_of_consultation) {
                return [
                    'id' => $collect->id, 
                    'consultation_name' => $collect->consultation_name,
                    'price' => isset($types_of_consultation->{$collect->id})?$types_of_consultation->{$collect->id}:'',
                ];
            });
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function addTypeOfConsultation(Request $request)
    {
        try {
            $user = \Auth::guard('api')->user();
          
            if ($user->is_doctor == 0)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);
          
            $validator = Validator::make($request->all(), [
                'consultation_ids' => 'required',
                // 'consultation_ids.*' => 'numeric|exists:type_of_consultations,id',
                 'price' => 'required',
                // 'price.*' => 'numeric',
            ]);
            $consultation_ids = explode(",",$request->consultation_ids);
            $price = explode(",",$request->price);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $data = [];
            // $symptomsdata = [];

            foreach ($consultation_ids as $key => $value) {

                $data[$value] = $price[$key];
              
            }

            $user->types_of_consultation = json_encode($data);
        

            $user->save();

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function doctorTypeOfConsult()
    {
        try {
            $user = \Auth::guard('api')->user();

            if (!$user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            if (empty($user->types_of_consultation))
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $doctorconsult_ids = array_keys(json_decode($user->types_of_consultation, true));

            $data = TypeOfConsultation::whereIn('id', $doctorconsult_ids)->where('status', 1)->get()->map(function ($collect) {
                return ['id' => $collect->id, 'name' => $collect->consultation_name];
            });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function deleteQuestionForDoctor(Request $request)
    {

        try {
            $user = \Auth::guard('api')->user();

            if (!$user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $validator = Validator::make($request->all(), [
                'question_id' => 'required|numeric|exists:question_for_patients,id'
            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $question = questions::where('id', $request->question_id)->first();
            $question->delete();

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }



    public function addDoctorQuestion(Request $request)
    {
        try {

            $user = \Auth::user();

            if (!$user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $validator = Validator::make($request->all(), [
                'types_of_consultation_id' => 'required|numeric|exists:type_of_consultations,id',
                'question' => 'required|string',
            ]);
        
        
            if ($validator->fails())
            return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);
      

            $question = questions::create([
                'doctor_id' => $user->id,
                'question' => $request->question,
                'types_of_consultation_id' => $request->types_of_consultation_id,
            ]);
           

            // $user->speciality()->sync($request->speciality_id);
         
            // $user->speciality->symptoms()->sync($request->symptom_id);
            if ($question)
                return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $question);
                
            return \ResponseBuilder::fail(trans('messages.SOMETHING'), $this->badRequest);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }


    }
    public function updateSymptomStatus(Request $request)
    {
        try {

            $user = \Auth::user();

            if (!$user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $validator = Validator::make($request->all(), [
                'types_of_consultation_id' => 'required|numeric|exists:type_of_consultations,id',
                'is_symptom'=>'required|in:0,1',
            ]);
        
        
            if ($validator->fails())
            return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            ConsultaionSymptom::updateOrCreate([
                'doctor_id'  => $user->id,
                'consultation_id'  => $request->types_of_consultation_id,
                ],[
                 'is_symptom'  => $request->is_symptom,
                ]);

            // $user->speciality()->sync($request->speciality_id);
         
            // $user->speciality->symptoms()->sync($request->symptom_id);
          
                return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success,);
                
            return \ResponseBuilder::fail(trans('messages.SOMETHING'), $this->badRequest);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }


    }

    public function getDoctorTypeQuestion(Request $request)
    {
        try {

            $user = \Auth::guard('api')->user();

            if (!$user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $q = questions::where('status', 1)->where('doctor_id', $user->id);

            if (isset($request->type_of_consultation_id))
                $q->where('types_of_consultation_id', $request->type_of_consultation_id);

            $data['question'] = $q->get()->map(function ($collect) {
                return [
                    'id' => $collect->id,
                    'question' => $collect->question
                ];
            });
            $ConsultaionSymptom = ConsultaionSymptom::where('doctor_id',$user->id)->where('consultation_id',$request->type_of_consultation_id)->first();
            $data['symptoms']  =  !empty($ConsultaionSymptom->is_symptom) ? $ConsultaionSymptom->is_symptom : 0;
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }


}