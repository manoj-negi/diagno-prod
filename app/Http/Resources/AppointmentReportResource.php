<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"                => $this->id,
            'patient_id'             => $this->patient_id,
            'test_name'               => isset($this->test->test_name) ? $this->test->test_name :'',
            'lab_name'               => isset($this->hospital->name) ? $this->hospital->name :'',
            'appointment_id'            => $this->appointment_id,
            'report_image'     =>url('uploads/appointment',$this->report_image) ,
        ];
    }
}
