<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Appointment;

class HospitalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $reviewCount = $this->ratingsReviews()->count();
        // $ratingCount = User::AVGRating($this->id);
        $patientCount = Appointment::where('hospital_id',$this->id)->groupBy('patient_id')->count();
        return [
            "id"                => $this->id,
            'name'              => $this->name ?? '',
            'address'           => $this->address ?? '',
            'email'             => $this->email ?? '',
            'hospital_category' => $this->hospital_category ?? '',
            // 'cities'          => $this->cities ?? [],
            // 'state'           => $this->state ?? '',
            'hospital_description'  => $this->hospital_description,
            'hospital_logo'     => $this->hospital_logo ? asset('uploads/hospital/' . $this->hospital_logo) : '',
            // 'review_count' => $reviewCount,
            'patientCount' => $patientCount,
            'avg_rating_count' => number_format($ratingCount ?? 0,2)
        ];
    }
}
