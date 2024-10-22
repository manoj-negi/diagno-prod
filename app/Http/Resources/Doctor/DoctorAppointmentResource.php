<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DoctorAppointmentResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return $this->collection->map(function ($data) {

            $dob = new \DateTime($data->patient->dob);
            $currentDate = new \DateTime(date('Y-m-d'));
            $age = $dob->diff($currentDate);
            return [
                'appointment_id' => $data->id,
                'date' => $data->date,
                'time' => $data->time,
                'patient_id'=>$data->patient->id,
                'patient_name' => $data->patient->name,
                'age' => $age->y,
                'appointment_type' => $data->consult_type,
                'consult _type' => $data->consultType?->consultation_name,
                'is_consult_completed' => $data->is_consult_completed
            ];
        });
    }
}
