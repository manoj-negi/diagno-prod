<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Doctor\DoctorDetailsResource;
use App\Http\Resources\Doctor\DoctorResource;
use App\Http\Resources\Doctor\AvaliablitiesCollection;
use App\Models\Avaliablity;
use App\Models\questions;
use App\Models\RatingReview;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use File;

class DoctorController extends Controller
{
    public function doctorList()
    {
        try {
            $doctor = User::where('status', 1)->where('is_approved', 'approved')->where('is_doctor', 1)->whereNotNull('subscription_inventory_id')->where('is_profile', 1)->paginate(12);

            if ($doctor->count() == 0)
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $data = new DoctorResource($doctor);

            return \ResponseBuilder::successWithPaginate(trans('messages.SUCCESS'), $this->success, $doctor, $data);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }


    public function getAvaliablity()
    {
        $user = \Auth::user();

        $avaliablity = Avaliablity::where('doctor_id', '47')->groupBy('hospital_id')->select('hospital_id')->get();
        $avaliablity = $avaliablity->map(function ($collect) use ($user) {
            return [
                'hospital_id' => $collect->hospital_id,
                'hospital_name' => $collect->hospitals->name ?? '',
                'avaliablity' => new AvaliablitiesCollection($this->getAvaliablityByDoctor($collect->hospitals->id, $user->id)),
            ];
        });

        return $avaliablity;

    }

    public function doctorFilters(Request $request)
    {
        try {
            // return $request;
            $validator = Validator::make($request->all(), [
                'name' => 'string',
                'speciality' => 'numeric|exists:specialities,id',
                'hospital' => 'numeric|exists:hospitals,id',
                'weekday' => 'numeric|min:1|Max:7',
                'start_time' => 'date_format:H:i',
                'end_time' => 'date_format:H:i|after:time_start',
                'pagination' => 'numeric',
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $pagination = isset($request->pagination) && $request->pagination > 0 ? (int) $request->pagination : 12;

            $q = User::where('status', 1)->where('is_approved', 'approved')->where('is_doctor', 1)->whereNotNull('subscription_inventory_id')->where('is_profile', 1);
            if ($request->name)
                $q->where('name', 'LIKE', "%$request->name%");

            if ($request->speciality)
                $q->whereHas('speciality', function ($query) use ($request) {
                    $query->where('id', $request->speciality)->where('status', 1);
                });

            if ($request->hospital)
                $q->whereHas('hospitals', function ($query) use ($request) {
                    $query->where('id', $request->hospital)->where('status', 1);
                });

            if ($request->weekday || $request->start_time || $request->end_time){
                $q->whereHas('availability', function ($query) use ($request) {
                    if ($request->weekday)
                        $query->where('week_day', $request->weekday)->where('status', 1);
                    if ($request->start_time)
                        $query->where('start_time', '<=', $request->start_time)->where('status', 1);
                    if ($request->end_time)
                        $query->where('end_time', '>=', $request->end_time)->where('status', 1);
                    $query->where('status', 1);
                });
            }

            $filter = $q->orderBy('name', 'DESC')->paginate($pagination)->withQueryString();
            if ($filter->count() == 0)
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $data = new DoctorResource($filter);

            return \ResponseBuilder::successWithPaginate(trans('messages.SUCCESS'), $this->success, $filter, $data);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function doctorDetails(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|numeric|exists:users,id,is_doctor,1'
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $doctor = User::where('id', $request->id)->where('status', 1)->where('is_approved', 'approved')->where('is_doctor', 1)->whereNotNull('subscription_inventory_id')->first();

            if (!$doctor)
                return \ResponseBuilder::fail('Not Authorized.', $this->badRequest);
            // return 'fd';

            $data = new DoctorDetailsResource($doctor);

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);


        } catch (\Exception $e) {
            return $e;
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }



    public function DoctorRatingReview(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required|numeric|exists:users,id'
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $ratings = RatingReview::where('doctor_id', $request->doctor_id)->paginate(5);

            if ($ratings->count() == 0)
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $data = [];

            foreach ($ratings as $rating) {
                $data[] = [
                    'id' => $rating->id,
                    'profile_image' => $rating->patient_details['profile_image'] ? url('uploads/profile-imges') . '/' . $rating->patient_details['profile_image'] : '',
                    'name' => $rating->patient_details['name'],
                    'ratings' => $rating->ratings,
                    'review' => $rating->review,
                    'date' => date('Y-m-d', strtotime($rating->created_at))
                ];
            }


            return \ResponseBuilder::successWithPaginate(trans('messages.SUCCESS'), $this->success, $ratings, $data);



        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function doctorProfile()
    {

        try {

            $doctor = \Auth::user();

            if (!$doctor->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $data = new DoctorDetailsResource($doctor);

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {

            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function getDoctorHospitalAvailability(Request $request)
    {

        try {

            $doctor = \Auth::user();

            if (!$doctor->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $doctor_hospital = $doctor->hospitals()->get()->map(function ($collect) {
                return ['hospital_id' => $collect->id, 'name' => $collect->name];
            });

            $doctoravail = Avaliablity::where('doctor_id', $doctor->id);

            if (isset($request->hospital_id))
                $doctoravail->where('hospital_id', $request->hospital_id);
            else
                $doctoravail->where('hospital_id', $doctor_hospital[0]['hospital_id']);

            $data = new AvaliablitiesCollection($doctoravail->get());

            $this->response = ['by_turn_time' => $doctor->by_turn_time, 'hospital_list' => $doctor_hospital, 'availability' => $data];

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $this->response);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function updateDoctorHospitalAvailability(Request $request)
    {
        try {

            $user = \Auth::user();
            if (!$user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $validator = Validator::make($request->all(), [
                'by_turn_time' => 'required|numeric|in:0,1',
                'availability' => 'required|json'
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $availability = json_decode($request->availability, true);

            if (count($availability) != 7)
                return \ResponseBuilder::fail('all seven weeks are required.', $this->badRequest);

            $keycheck = ['id', 'hospital_id', 'week_day', 'start_time', 'end_time', 'status'];

            $data = [];
            foreach ($availability as $value) {

                if (count(array_intersect($keycheck, array_keys($value))) != 6)
                    return \ResponseBuilder::fail('need same keys with same count.', $this->badRequest);
                $value['doctor_id'] = $user->id;
                $data[] = $value;
            }

            Avaliablity::upsert($data, ['id'], ['start_time', 'end_time', 'status']);

            $user->by_turn_time = $request->by_turn_time;
            $user->is_profile = 1;
            $user->save();

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function updateDoctorProfile(Request $request)
    {
        try {
            $user = \Auth::user();
            if (!$user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $validator = Validator::make($request->all(), [
                'name' => 'string',
                'profile_image' => 'image|mimes:jpeg,png,jpg',
            ]);

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
            // $user->age = $request->age ? $request->age : $user->age;
            // $user->sex = $request->sex ? $request->sex : $user->sex;
            $user->name = $request->name ? $request->name : $user->name;
            $user->last_name = $request->last_name ? $request->last_name : $user->last_name;
            $user->experience = isset($request->experience) ? $request->experience : $user->experience;
            $user->exequatur_number = isset($request->exequatur_number) ? $request->exequatur_number : $user->exequatur_number;
            $user->address = isset($request->address) ? $request->address : $user->address;
            $user->doctor_bio = isset($request->bio) ? $request->bio : $user->doctor_bio;
            $user->save();
            if (isset($request->specialty_ids))
                $user->speciality()->sync(explode(',', $request->specialty_ids));

            if (isset($request->hospital_ids)) {
                $prev = $user->hospitals()->pluck('id')->toArray();
                $new = explode(',', $request->hospital_ids);
                $deleteId = array_diff($prev, $new);
                
                if (count($deleteId) > 0)
                    Avaliablity::whereIn('hospital_id', $deleteId)->where('doctor_id', $user->id)->delete();

                $addId = array_diff($new, $prev);

                if (count($addId) > 0) {
                    $data = [];
                    foreach ($addId as $key => $value) {
                        for ($i = 1; $i <= 7; $i++) {
                            $data[] = ['doctor_id' => $user->id, 'hospital_id' => $value, 'week_day' => $i];
                        }
                    }
                    Avaliablity::insert($data);
                }
                $user->hospitals()->sync($new);
            }
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success);

        } catch (\Exception $e) {

            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function editBio(Request $request)
    {
        try {
            $user = \Auth::user();

            if (!$user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

            $validator = Validator::make($request->all(), [
                'doctor_bio' => 'required|string'
            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $user->doctor_bio = $request->doctor_bio;
            $user->save();

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }


    public function updateDoctorAvailability(Request $request)
    {

        try {
            $user = \Auth::user();

            if (!$user->is_doctor)
                return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

              
            $validator = Validator::make($request->all(), [
                'by_turn_time' => 'required|numeric',
                'week_days'   =>  'required|array|size:7',
                'hospital_id' =>  'required'
            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $ids = Avaliablity::where('doctor_id', $user->id)->where('hospital_id', $request->hospital_id)->pluck('id')->toArray();

            $data = [];
            foreach ($request->week_days as $key => $value) {
                $time = explode(',', $value);

                $data[] = [

                    'doctor_id' => $user->id,
                    'hospital_id' => $request->hospital_id,
                    'start_time' => $time[0],
                    'end_time' => $time[1],
                    'week_day' => $key + 1,
                    'status' => $time[2]
                ] + (!empty($ids) ? ['id' => $ids[$key]] : []);
            }

            $avaliable = Avaliablity::upsert($data, ['id', 'doctor_id', 'week_day'], ['start_time', 'end_time', 'status']);

            if ($avaliable) {

                $user->by_turn_time = $request->by_turn_time;
                $user->is_profile = 1;
                $user->save();

                return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success);
            }

            return \ResponseBuilder::fail(trans('messages.SOMETHING'), $this->badRequest);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    // public function QuestionForPatient(Request $request)
    // {
    //     try {

    //         $user = \Auth::user();

    //         if (!$user->is_doctor)
    //             return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

    //         $validator = Validator::make($request->all(), [
    //             'speciality_id' => 'numeric|exists:specialities,id'
    //         ]);

    //         if ($validator->fails())
    //             return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

    //         if ($request->speciality_id) {
    //             $question = questions::where('speciality_id', $request->speciality_id)->where('status', 1)->pluck('question')->toArray();

    //             if (count($question) == 0)
    //                 return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);
    //             return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $question);
    //         }

    //         $specialities = $user->speciality;

    //         if ($specialities->count() == 1) {
    //             $question = questions::where('speciality_id', $specialities[0]->id)->where('status', 1)->pluck('question')->toArray();
    //             if (count($question) == 0)
    //                 return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);
    //             return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $question);
    //         }

    //             $data = [];
    //             foreach ($specialities as $speciality) {
    //             $data[] = ['id' => $speciality->id, 'name' => $speciality->name];
    //         }

    //         return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

    //     } catch (\Exception $e) {
    //         return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
    //     }
    // }

    // public function addQuestion(Request $request)
    // {

    //     try {

    //         $user = \Auth::user();

    //         if (!$user->is_doctor)
    //             return \ResponseBuilder::fail(trans('messages.NOT_AUTHORIZED'), $this->badRequest);

    //         $validator = Validator::make($request->all(), [
    //             'speciality_id' => 'required|numeric|exists:specialities,id',
    //             'question' => 'required|string'
    //         ]);

    //         if ($validator->fails())
    //             return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

    //         $question = questions::create([
    //             'question' => $request->question,
    //             'speciality_id' => $request->speciality_id
    //         ]);

    //         if ($question)
    //             return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->badRequest);
    //         return \ResponseBuilder::fail(trans('messages.SOMETHING'), $this->badRequest);

    //     } catch (\Exception $e) {
    //         return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
    //     }

    // }
    public function doctorhospital()
    {
        try {
            $user = \Auth::user();
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success,$user->hospitals);
        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->badRequest);
        }

    }


}