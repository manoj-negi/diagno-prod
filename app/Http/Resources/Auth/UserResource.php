<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name ?? '',
            'last_name' => $this->last_name ?? '', 
            'email' => $this->email ?? '',
            'number' => $this->number ?? '',
            'wallet' => number_format($this->wallet,2),
            'refer_code' => $this->refer_code ?? '',
            'address' => $this->address ?? '',
            'dob' => $this->dob ?? '',
            'is_profile_complete' => $this->is_profile ? true : false,
            'profile_image' => $this->profile_image ? url('uploads/profile-imges') . '/' . $this->profile_image : '',
        ];

    }
}