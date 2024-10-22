<?php

namespace App\Http\Resources\Patient;

use Illuminate\Http\Resources\Json\ResourceCollection;
use File;
use App\Helper\Helper;
use Illuminate\Support\Facades\Storage;
class PatientReportResource extends ResourceCollection
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
                'report' => !empty($data->report_image) ? url('/img/reports') . '/' . $data->report_image : '',
                'name' => $data->report_image,
                'report_title' => $data->report_title,
                'date' => date('d M Y', strtotime($data->created_at)),
                'size' => !empty($data->report_image) ? Helper::formatSizeUnits(File::size(public_path("/img/reports/$data->report_image"))) : 0,
            ];

        });
    }
}
