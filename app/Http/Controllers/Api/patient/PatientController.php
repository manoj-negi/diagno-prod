<?php

namespace App\Http\Controllers\Api\patient;
use App\Http\Controllers\Controller;
use App\Http\Resources\Patient\PatientReportResource;
use App\Http\Resources\Doctor\DoctorDetailsResource;
use App\Models\Appointment;
use App\Models\AppointmentReport;
use App\Models\ConsultDetail;
use App\Models\questions;
use App\Models\RatingReview;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use File;
use DB;
use Illuminate\Support\LazyCollection;
class PatientController extends Controller
{
    public function PatientProfile()
    {

        try {
            $user = \Auth::user();

            if ($user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);


            $appointments = Appointment::where('patient_id', $user->id)
                ->where('status', 1)
                ->get()->count();

            $questions = questions::select('question')->where('doctor_id', $user->id)->orWhere('doctor_id', 0)
                ->where('status', 1)
                ->get();


            $consult = ConsultDetail::where('patient_id', $user->id)->where('status', 1)->get()->count();

            $dob = new \DateTime($user->dob);
            $currentDate = new \DateTime(date('Y-m-d'));
            $age = $dob->diff($currentDate);

            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name ?? '',
                'number' => $user->number,
                'email' => $user->email,
                'dob' => $user->dob,
                'marital_status' => $user->marital_status,
                'emergency_contact' => $user->emergency_contact,
                'cedula' => $user->cedula,
                'address' => $user->address,
                'profile_image' => url('uploads/profile-imges') . '/' . $user->profile_image,
                'appointments' => $appointments,
                'age' => $age->y,
                'insurance_number' => $user->insurance_number,
                'preset_question' => $questions,
                'previous_consult' => $consult,
            ];
            // 'age' => $user->age,
            // 'sex' => $user->sex,
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function createGetPresetQuestionAnswer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|numeric|exists:users,id,is_doctor,0',
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);


            $questions = questions::
                leftJoin('preset_question_answers', 'question_for_patients.id', '=', 'preset_question_answers.question_id')
                ->select('question_for_patients.*', 'preset_question_answers.*')
                ->where('preset_question_answers.patient_id', $request->patient_id)->where('question_for_patients.doctor_id', 0)
                ->where('question_for_patients.status', 1)
                ->get();

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $questions);
        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    // public function updatePresetAnswer(Request $request)
    // {
    //     try{
    //         $user = \Auth::user();
    //          if ($user->is_doctor)
    //             return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);
    //         $validator = Validator::make(request->all(),
    //             [
    //               'question_id' ='required',
    //               ' answer'     = 'required'
    //         ]);
    //         if($validator->fails())
    //             return \ResponseBuilder::fail($validator->errors()->first(),$this->badRequest);

    //          'question_id' = $request->question_id;
    //          'answer' = $request->answer ;
    //           $user->save();

    //          return \ResponseBuilder::success(trans('messages.PROFILE_UPDATE'), $this->success);

    //     }
    //      catch (\Exception $e) {
    //         return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
    //     }
    // }



    public function UpdatePatientProfile(Request $request)
    {
        try {
            $user = \Auth::user();

            if ($user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $validator = Validator::make($request->all(), [
                'name' => 'string',
                'last_name' => 'string',
                'dob' => 'date_format:Y-m-d',
                'profile_image' => 'image|mimes:jpeg,png,jpg',
                'marital_status' => 'in:,unmarried,married',
                'emergency_contact' => 'numeric|digits_between:8,12',
                'cedula' => 'nullable|numeric|min:11',
            ]);
            // 'age' => 'numeric|max:150',
            // 'sex' => 'in:Male,Female,Non binary',

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            if (isset($request->profile_image)) {

                $imagename = $this->uploadDocuments($request->file('profile_image'), public_path('/uploads/profile-imges/'));
                $oldimage = $user->profile_image;
                $user->profile_image = $imagename;
                $user->save();
                if ($oldimage != 'user.png') {

                    if (File::exists(public_path('/uploads/profile-imges/' . $oldimage)))
                        File::delete(public_path('/uploads/profile-imges/' . $oldimage));
                }
            }
            $user->name = $request->name ? $request->name : $user->name;
            $user->last_name = $request->last_name ? $request->last_name : $user->last_name;
            $user->insurance_number = isset($request->insurance_number) ? $request->insurance_number : $user->insurance_number;
            $user->email = $request->email ? $request->email : $user->email;
            $user->number = $request->number ? $request->number : $user->number;
            $user->emergency_contact = isset($request->emergency_contact) ? $request->emergency_contact : $user->emergency_contact;
            $user->dob = isset($request->dob) ? $request->dob : $user->dob;
            $user->marital_status = isset($request->marital_status) ? $request->marital_status : $user->marital_status;
            $user->cedula = isset($request->cedula) ? $request->cedula : $user->cedula;
            $user->address = isset($request->address) ? $request->address : $user->address;
            $user->save();

            return \ResponseBuilder::success(trans('messages.PROFILE_UPDATE'), $this->success);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function patientReport(Request $request)
    {
        try {
            $user = \Auth::user();
            $id = $user->id;
            if (!isset($request->patient_id)) {
                if ($user->is_doctor)
                    return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);
            } else {

                $validator = Validator::make($request->all(), [
                    'patient_id' => 'required|numeric|exists:users,id,is_doctor,0'
                ]);
                if ($validator->fails())
                    return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

                $id = $request->patient_id;
            }

            $reports = AppointmentReport::where('patient_id', $id)->orderBy('id', 'DESC')->get();

            $data = new PatientReportResource($reports);
            // return $data;
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function medicineTaken()
    {
        try {

            $user = \Auth::user();
            if ($user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $consult = ConsultDetail::where('patient_id', $user->id)->where('status', 1)->orderBy('id', 'DESC')->paginate(5);

            if (!$consult->count())
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $data = $consult->map(function ($collect) {

                $medicine_taken = json_decode($collect->medicine_taken, true);
                $medicine = $collect->medicineName(explode(',', $collect->medication_ids))->map(
                    function ($med) use ($medicine_taken) {
                        if(isset($medicine_taken[$med->id]))
                        return ['id' => $med->id, 'name' => $med->name, 'taken' => $medicine_taken[$med->id]];
                    }
                );
                // $medicine =  (array_filter($medicine->toArray(), fn($value) => !is_null($value) && $value !== ''));
                // $medicine = (object)$medicine;
                return ['appointment_id' => $collect->appointment_id,'doctor_name' => $collect->doctor->name ?? '', 'medicine' => $medicine];
            });

            return \ResponseBuilder::successWithPaginate(trans('messages.SUCCESS'), $this->success, $consult, $data);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function medicineTakenAction(Request $request)
    {

        try {

            $user = \Auth::user();
            if ($user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $validator = Validator::make($request->all(), [
                'appointment_id' => 'required|numeric|exists:consult_details,appointment_id,status,1',
                'medicine_id' => 'required|numeric|exists:medications,id'
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $consult = ConsultDetail::where('appointment_id', $request->appointment_id)->first();

            if(!$consult)
            return \ResponseBuilder::fail('Invaild appointment id', $this->badRequest);

            $medicineTaken = json_decode($consult->medicine_taken, true);
    
            $medicineTaken[$request->medicine_id] = 1;
         
            $consult->medicine_taken = json_encode($medicineTaken);
            $consult->save();
          
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function giveReview(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required|exists:appointments,token',
                'ratings' => 'required|numeric|between:1,5',
                'review' => 'string'

            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);
                
            $appointment = Appointment::where('token',$request->token)->first();
         
            RatingReview::create([
                'doctor_id'  => $appointment->doctor_id ?? '',
                'patient_id' => $appointment->patient_id ?? '',
                'ratings'    =>  $request->ratings ?? '',
                'review'     =>  $request->review ?? '',
            ]);
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }
    public function reviewGetDoctorDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required|exists:appointments,token',
            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

          
            $appointment = Appointment::where('token',$request->token)->first();
            $dataDoctor = $appointment->doctor;
            $data = [
                'name' => ($dataDoctor->name ?? '') .''. ($dataDoctor->last_name ?" ".$dataDoctor->last_name: ''),
                'profile_image' => $dataDoctor->profile_image ? url('uploads/profile-imges') . '/' . $dataDoctor->profile_image : '',
                'specialities' => $dataDoctor->speciality->map(function($value){
                    return [
                        'name' => $value->name,
                    ];
                }),

                'ratings' => number_format((float)$dataDoctor->DoctorRatings($dataDoctor->id),0,''),
                'review_count' => $dataDoctor->ratingReview->count(),
            ];
            return $data;
            $dataDoctor = new DoctorDetailsResource($appointment->doctor);
           
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success,$dataDoctor);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }
    public function uploadUsersCsv(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file',
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);
                DB::beginTransaction();
                if ($request->hasFile('file')) {
                    $fileName = $this->uploadDocuments($request->file('file'), public_path('/uploads/profile-imges/'));
                    LazyCollection::make(function () use ($fileName) {
                        $handle = fopen(public_path('/uploads/profile-imges/'.$fileName), 'r');
                        while (($line = fgetcsv($handle, 4096)) !== false) {
                          $dataString = implode(", ", $line);
                          $row = explode(';', $dataString);
                          yield $row;
                        }
                        fclose($handle);
                      })
                      ->skip(1)
                      ->chunk(1000)
                      ->each(function (LazyCollection $chunk) {
                        $records = $chunk->map(function ($row) {
                            $implode = explode(',',$row[0]);
                          return [
                              "name"   => $implode[0] ?? '',
                              "email"  => $implode[1] ?? '', 
                              "number" => $implode[2] ?? '',
                          ];
                        })->toArray();
                        foreach($records as $item){
                            User::updateOrCreate([
                                'email' => $item['email'] ?? '',
                            ],[
                              'number'  => $item['number'] ?? '',
                              'name'    => $item['name'] ?? '',
                            ]);
                           
                        }
                       
                      });
                    }
                    DB::commit();
            return \ResponseBuilder::success('Insert Done', $this->success);

        } catch (\Exception $e) {
            DB::rollBack();
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }









}