<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AvailabilityResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $week_days = [
            '1' => 'sunday',
            '2' => 'monday',
            '3' => 'tuesday',
            '4' => 'wednesday',
            '5' => 'thursday',
            '6' => 'friday',
            '7' => 'saturday'
        ];
        return $this->collection->map(function ($data) use ($week_days) {
            return [
                'day' => $week_days[$data->week_day],
                'start_time' => $data->start_time,
                'end_time' => $data->end_time,
                'status'=>$data->status, 
                'hospital_id'=>$data->hospital_id,
            ];
        });
    }
}
