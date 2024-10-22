<?php

namespace App\Http\Resources\Lab;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProfilePreviewCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function($data) {
            return [
                "id"                => $data->id ?? '',
                "package_name"      => $data->profile_name ?? '',            
                "amount"            => !empty($data->amount) ? $data->amount : '',            
                "image"             => !empty($data->image) ? url('uploads/profile',$data->image) : '',            
            ];
        });
    }
}