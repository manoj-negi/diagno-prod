<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Helper\Helper;
class AvaliablitiesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $heading = '';
        $retAvblity = [];
        foreach ($this->collection as $key => $value) {

            if (isset($value->hospitals->name) && $heading !== $value->hospitals->name) {
                $heading = $value->hospitals->name;
                $retAvblity[] = [
                    'id' => $value->id,
                    'doctor_id' => $value->doctor_id,
                    'hospital_id' => $value->hospital_id,
                    'hospital_name' => $value->hospitals->name ?? '',
                    'week_day' => $value->week_day,
                    'week_name' => Helper::weekDays()[$value->week_day] ?? '',
                    'start_time' => $value->start_time,
                    'end_time' => $value->end_time,
                    'status' => $value->status,
                    'heading' => $value->hospitals->name ?? ''
                ];

            } else {
                $retAvblity[] = [
                    'id' => $value->id,
                    'doctor_id' => $value->doctor_id ?? '',
                    'hospital_id' => $value->hospital_id ?? '',
                    'hospital_name' => $value->hospitals->name ?? '',
                    'week_day' => $value->week_day ?? '',
                    'week_name' => Helper::weekDays()[$value->week_day] ?? '',
                    'start_time' => $value->start_time ?? '',
                    'end_time' => $value->end_time ?? '',
                    'status' => $value->status ?? ''
                ];

            }
        }
        return $retAvblity;
    }
}