<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class LabTestResource extends JsonResource
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
            'id'                => $this->id,
            'hospital_id'       => isset($this->user->name) ? $this->user->name :'',,
            'test_name'         => $this->test_name,
            'amount'            => $this->amount,
            'description'       => $this->description,
            'admin_status'      => $this->admin_status,
            // 'password'          => $this->password,
            // 'name'              => $this->name,
            //  'state'           => $this->state,
            //  'hospital_description'  => $this->hospital_description,
            //  'hospital_logo'     => $this->hospital_logo ? asset('uploads/hospital/' . $this->hospital_logo) : null,
        ];
    }
}
