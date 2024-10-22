<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TypeOfConsultation;

class DoctorDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $consultation = $this->doctorConsultation($this->types_of_consultation);
        return [
            'id' => $this->id,
            'profile_image' => $this->profile_image ? url('uploads/profile-imges') . '/' . $this->profile_image : '',
            'name' => ($this->name ?? '') .''. ($this->last_name ?" ".$this->last_name: ''),
            'number' => $this->number ?? '',
            'email' => $this->email ?? '',
            'specialities' => $this->speciality->map(function($value){
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'symptom'=>$value->symptoms
                ];
            }),
            'is_online' => $this->is_online,
            'hospitals' => $this->hospitals->map(function($value){
                return [
                    'id' => $value->id,
                    'name' => $value->name
                ];
            }),
            'experience' => $this->experience,
            'doctor_bio' => $this->doctor_bio,
            'by_turn_time' => $this->by_turn_time,
            'is_virtual' => $this->is_virtual,
            'ratings' => (double) $this->DoctorRatings($this->id),
            'exequatur_no' => $this->exequatur_number,
            'patient' => $this->consult->count(),
            'review_count' => $this->ratingReview->count(),
            'avaliablity' => new AvaliablitiesCollection($this->availability()->where('status', 1)->get()),
            'consultation' => $consultation, //(count($consultation) > 0)?$consultation:[]
            'subscription_id' => isset($this->subscription) && isset($this->subscription->subscriptions->id) ? ($this->subscription->subscriptions->id ?? '') : ''  ,
            'subscription_amount' => isset($this->subscription) && isset($this->subscription->amount) ? ($this->subscription->amount ?? '') : ''  ,
            'subscription_currency' => isset($this->subscription) && isset($this->subscription->currency) ?  ($this->subscription->currency ?? '') : ''  ,
            'subscription' => isset($this->subscription) && isset($this->subscription->subscriptions) ?  ($this->subscription->subscriptions->title ?? '') : ''   ,
        ];
        // 'educations' => $this->educations->pluck('name')->toArray(),
    }

    public function doctorConsultation($types_of_consultation = '')
    {
        # code...
        if($types_of_consultation == '') {
            return [];
        }
        $consultationKeys = json_decode($types_of_consultation, true);
        $keys = array_keys($consultationKeys);
        // return gettype($keys); 
        $data = TypeOfConsultation::whereIn('id', $keys)->get()->map(function ($collect) use($consultationKeys) {
            return [
                'id' => $collect->id, 
                'consultation_name' => $collect->consultation_name, 
                'price' => $consultationKeys[$collect->id]
            ];
        });
        return $data;
    }
}
