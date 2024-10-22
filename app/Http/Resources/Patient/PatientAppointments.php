<?php

namespace App\Http\Resources\Patient;

use App\Models\Disease;
use App\Models\MedicalTest;
use App\Models\Medication;
use App\Models\AppointmentReport;
use App\Models\Appointment;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helper\Helper;

class PatientAppointments extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(isset($this->appointment_id)){

            $appointmentReports = AppointmentReport::where('patient_id', $this->patient_id)->pluck('report_image')->toArray();
            $collects = [];
            if (count($appointmentReports)) {
                $collects = collect($appointmentReports)->map(function ($collect) {
                    return url('/img/reports') . '/' . $collect;
                });
            }

            $appointment = Appointment::where('id', $this->appointment_id)->first();
            $tests = MedicalTest::whereIn('id', explode(',', $this->test_ids))->pluck('name')->toArray();
            $disease = Disease::where('id', $this->disease_id)->pluck('name')->toArray();
            $medicines = Medication::whereIn('id', explode(',', $this->medication_ids))->pluck('name', 'id')->toArray();
            $medicineTaken = json_decode($this->medicine_dosage, true);
            $medicineComment = json_decode($this->medicines_comment, true);
            $medicineData = Helper::medicineTaken($medicineTaken, $medicines,$medicineComment);
            return [
                'date' => date('Y-m-d', strtotime($this->created_at)),
                'tests' => $tests ?? [],
                'doctor_name' => $appointment->doctor->name,
                'disease' => $disease,
                'medicines' => $medicineData ?? [],
                'blood_test' => Helper::appointmentBloodTest($this->blood_test_ids),
                'symptom' => Helper::appointmentSymptom($this->symptom_ids),
                'blood_test_doctor_comment' =>($this->blood_tests_other_data ?? ''),
                'medical_test_doctor_comment' =>($this->medical_tests_other_data ?? ''),
                'medicines_taken_time' =>(json_decode($this->medicine_dosage,true)),
                'doctor_checkups' =>(Helper::consultDoctorCheckups($this->id)),
                'next_appointment_date' => $this->next_appointment_date  ?? '',
                'doctor_comment' => $this->doctor_opinion ?? '',
                'reports' => $collects ?? [],
            ];
        }

    }
}