<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'offer_id' => $this->offer_id,
            'title' => $this->title,
            'description' => $this->description,
            'discount_percentage' => $this->discount_percentage,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'terms_and_conditions' => $this->terms_and_conditions,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'offer_code' => $this->offer_code,
            'offer_type' => $this->offer_type,
            'maximum_discount' => $this->maximum_discount,
            'minimum_purchase_amount' => $this->minimum_purchase_amount,
            'applicable_to' => $this->applicable_to,
        ];
    }
}
