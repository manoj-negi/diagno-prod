<?php

namespace App\Http\Resources\Doctor;

use App\Models\Appointment;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $day = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24];
        $data = [];
        $appointment_time = Appointment::where('doctor_id', $request->doctor_id)->where('date', $request->date)->where('status', 1)->pluck('time')->toArray();
        
        for ($i = (int) $this->start_time; $i <= (int) $this->end_time; $i++) {
            $time = in_array(date('H:i', strtotime("$i:00:00")), $appointment_time) ? ['time' => date('H:i', strtotime("$i:00:00")), 'status' => 0] : ['time' => date('H:i', strtotime("$i:00:00")), 'status' => 1];

            if (in_array($i, $day))
                $data[] = $time;
        }

       return $data;

    }
}
