<?php

namespace App\Http\Resources\Patient;

use App\Models\Disease;
use App\Models\MedicalTest;
use App\Models\Medication;
use App\Models\AppointmentReport;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Helper\Helper;

class PatientDetailResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $appointmentReports = AppointmentReport::where('appointment_id', $request->appointment_id)->pluck('report_image')->toArray();

        $collects = [];
        if (count($appointmentReports)) {
            $collects = collect($appointmentReports)->map(function ($collect) {
                return url('/img/reports') . '/' . $collect;
            });
        }

        $current = [
            'date' => date('Y-m-d'),
            'tests' => [],
            'doctor_name' => \Auth::user()->name,
            'disease' => '',
            'medicines' => [],
            'doctor_comment' => '',
            'reports' => $collects ?? ''
        ];

        return $this->collection->map(function ($data) {

            $tests = MedicalTest::whereIn('id', explode(',', $data->test_ids))->pluck('name')->toArray();
            $disease = Disease::where('id', $data->disease_id)->pluck('name')->toArray()[0];
            $medicines = Medication::whereIn('id', explode(',', $data->medication_ids))->pluck('name', 'id')->toArray();
            $medicineTaken = json_decode($data->medicine_dosage, true);
            $medicineData = Helper::medicineTaken($medicineTaken, $medicines);
            return [
                'date' => date('Y-m-d', strtotime($data->created_at)),
                'tests' => $tests,
                'doctor_name' => $data->doctor->name,
                'disease' => $disease,
                'medicines' => $medicineData,
                'doctor_comment' => $data->doctor_opinion,
                'reports' => url('uploads/pdf') . '/' . $data->reports->report ?? '',
            ];
        })->prepend($current);
    }
}