<?php

namespace App\Http\Controllers\Api\Additonals;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\Hospital;
use App\Models\Speciality;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\Notification;
use Validator;


class AdditionalController extends Controller
{
    public function education()
    {
        try {
            $education = Education::select('id', 'name')->where('status', 1)->get();
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $education);

        } catch (\Exception $e) {

            return \ResponseBuilder::fail($e->getMessage(), $this->badRequest);
        }
    }

    public function hospital()
    {

        try {

            $hospital = Hospital::select('id', 'name', 'address', 'city')->where('status', 1)->get();
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $hospital);

        } catch (\Exception $e) {

            return \ResponseBuilder::fail($e->getMessage(), $this->badRequest);
        }

    }

    public function speciality()
    {
        try {

            $speciality = Speciality::select('id', 'name')->where('status', 1)->get();
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $speciality);

        } catch (\Exception $e) {

            return \ResponseBuilder::fail($e->getMessage(), $this->badRequest);
        }

    }

    public function subscription()
    {
        try {

            $subscription = Subscription::where('status', 1)->get();
            $data = $subscription->map(function ($collect) {
                return [
                    'id' => $collect->id,
                    'title' => $collect->title,
                    'price' => $collect->price,
                    'days' => $collect->days,
                    'plans' => $collect->plans->map(
                        function ($plan) {
                            return $plan->name;
                        }
                    )
                ];
            });
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function Notifications()
    {
        try {
            $user = \Auth::user();
            Notification::where('user_id', $user->id)->update(['is_read' => 1]);
            $data['unread_count'] = Notification::where('user_id', $user->id)->where('is_read', 0)->count();
            $data['notification'] = Notification::where('user_id', $user->id)->orderBy('id', 'DESC')->get()->map(function ($collect) {
                return [
                    'title' => $collect->title,
                    'message' => $collect->message,
                    'date' => date('Y-m-d', strtotime($collect->created_at)),
                    'time' => date('h:i A', strtotime($collect->created_at)),
                    'is_read' => $collect->is_read,
                ];
            });
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function updatePresetStatus()
    {
        try {
            $user = \Auth::guard('api')->user();
            $user->preset_question_status = 1;
            $user->save();

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }

    public function subscriptionDetail(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|numeric|exists:subscriptions,id'
            ]);

            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $subscription = Subscription::where('id', $request->id)->first();

            $data = [
                'id' => $subscription->id,
                'title' => $subscription->title,
                'days' => $subscription->days,
                'price' => $subscription->price,
                'plans' => $subscription->plans()->pluck('name')
            ];
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }


    }


}
