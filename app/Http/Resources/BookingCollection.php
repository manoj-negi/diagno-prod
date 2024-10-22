<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BookingCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
// class BookingCollection extends ResourceCollection
// {
//     public function toArray($request)
//     {
//         return [
//             'data' => $this->collection->transform(function ($booking) {
//                 return new BookingResource($booking);
//             }),
//         ];
//     }
// }