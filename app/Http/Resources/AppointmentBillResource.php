<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentBillResource extends JsonResource
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
            'amount'   => $this->amount,
            "document_file"     => url('uploads/appointment',$this->document_file),
            'title'      => $this->title,
            'appointment_id'     => $this->appointment_id,
         
        ];
    }
}
