<?php

namespace App\Http\Resources\Lab;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TestResource extends JsonResource
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
            "id"                => isset($this->id) ? $this->id : '',
            "test_name"         => isset($this->package_name) ? $this->package_name : '',            
            "amount"            => $this->amount ?? '0.00',            
            "lab_id"            => $this->lab_id ?? '',            
            "description"       => isset($this->description) ? $this->description : '',            
        ];
    }
}
