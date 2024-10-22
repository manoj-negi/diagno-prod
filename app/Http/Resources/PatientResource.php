<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            "id"                => $this->id ?? '',
            'email'             => $this->email ?? '',
            'name'              => $this->name ?? '',
            'gender'            => $this->sex ?? '',
            'city'              => $this->city ?? '',
            'dob'               => $this->dob ?? '',
            'address'           => $this->address ?? '',
            'number'            => $this->number ?? '',
            'profile_image'     => !empty($this->profile_image) ? url('uploads/patient',$this->profile_image) : '',
        ];
    }
}
