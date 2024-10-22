<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientBillResource extends JsonResource
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
            'patient_id'        => $this->patient_id,
            'title'             => $this->title,
            'image'             =>url('uploads/hospital',$this->image) ,
         
         


            
         
        ];
    }
}
