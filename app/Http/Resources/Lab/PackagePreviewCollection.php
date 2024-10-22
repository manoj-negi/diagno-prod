<?php

namespace App\Http\Resources\Lab;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PackagePreviewCollection extends ResourceCollection
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
                "package_name"      => $data->package_name ?? '',            
                "amount"            => !empty($data->amount) ? $data->amount : '',            
                "image"             => !empty($data->image) ? url('uploads/package',$data->image) : '',            
            ];
        });
    }
}