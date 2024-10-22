<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RatingReviewResource extends ResourceCollection
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
                'name' => $data['patient_name'],
                'ratings' => $data['ratings'],
                'review' => $data['review'],
                'date' => $data['created_at'],
            ];
        });
    }
}