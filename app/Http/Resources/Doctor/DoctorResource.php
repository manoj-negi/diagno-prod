<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DoctorResource extends ResourceCollection
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

            return [

                'id' => $data->id,
                'name' => ($data->name ?? '') .' '. ($data->last_name ?? ''),
                'profile_image' => $data->profile_image ? url('uploads/profile-imges') . '/' . $data->profile_image : '',
                'is_online' => $data->is_online,
                'specialities' => $data->speciality->pluck('name')->toArray(),
                'ratings' => (double)$data->DoctorRatings($data->id),
            ];
        });
    }
}