<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class HospitalDoctorResource extends JsonResource
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
            'doctor_category'             => $this->doctor_category,
            'doctor_age'               => $this->doctor_age,
            'doctor_name'            => $this->doctor_name,
            'hospital_id'            => $this->hospital_id,
           
          
         
         


            
         
        ];
    }
}
