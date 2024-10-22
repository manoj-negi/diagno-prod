<?php

namespace App\Http\Resources\Lab;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\HospitalResource;

class PackageResource extends JsonResource
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
            "id"                => isset($this->packageData) ? $this->packageData->id : '',
            "package_name"      => isset($this->packageData) ? $this->packageData->package_name : '',            
            "amount"            => !empty($this->amount) ? $this->amount : (isset($this->packageData) ? $this->packageData->amount : '0.00'),            
            "lab"               => isset($this->labDetails) && !empty($this->labDetails) ? new HospitalResource($this->labDetails) : '',            
            "profiles"          => isset($this->packageData->getChilds) && !empty($this->packageData->getChilds) ? new ProfileCollection($this->packageData->getChilds) : [],            
        ];
    }
}
