<?php

namespace App\Http\Resources;

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
        // return parent::toArray($request);
        return [
            'id'                => $this->id,
            'name'              => (string)$this->name??'',
            'email'             => (string)$this->email??'',
            'number'             => (string)$this->number??'',
            'status'            => (string)$this->status??'',
            'address'           => (string)$this->address??'',
            'country'           => (string)$this->country??'',
            'country_name'      => (string)$this->country_name??'',
            'state'             => (string)$this->state??'',
            'state_name'        => (string)$this->state_name??'',
            'city'              => (string)$this->city??'',
            'zip_code'          => (string)$this->zip??'',
            'profile_image'     => (string)$this->image??'',
            'role_id'           => $this->roles[0]->id??'',
            'role'              => $this->roles[0]->role??'',
        ];
    }
}
