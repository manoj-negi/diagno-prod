<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AppointmentAvailabilityResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    // protected $doctor_name;
    // public function __construct($doctor)
    // {
    //     $this->doctor_name = $doctor->name;
    // }

    public function toArray($request)
    {

        $week_days = $this->collection->map(function ($collect) {
            return $collect->week_day - 1;
        })->toArray();
        
        $currenttotaldays = date('t');
        $currentMonth = date('m');
        $currentYear = date('Y');

        $data = [];
        $data['appointment_id'] = $this->collection[0]->appointment_id;
        $data['doctor_name'] = $this->collection[0]->doctor_name;

        for ($i = 1; $i <= $currenttotaldays; $i++) {

            $day = date('w', strtotime("$currentYear-$currentMonth-$i"));

            if (in_array($day, $week_days) && date('Y-m-d') <= date('Y-m-d', strtotime("$currentYear-$currentMonth-$i"))){

                $data['current_month'][] = [
                    'day' => date('l', strtotime("$currentYear-$currentMonth-$i")), 
                    'date' => date('Y-m-d', strtotime("$currentYear-$currentMonth-$i")), 
                    'week_day' => $day + 1, 
                    "is_available" => 1,
                ];

            } else {

                $data['current_month'][] = [
                    'day' => date('l', strtotime("$currentYear-$currentMonth-$i")), 
                    'date' => date('Y-m-d', strtotime("$currentYear-$currentMonth-$i")), 
                    'week_day' => $day + 1,
                    "is_available" => 0,
                ];
            }
        }



        $nexttotaldays = date('t', strtotime('+1 month'));
        $nextMonth = date('m', strtotime('+1 month'));
        $nextYear = date('Y', strtotime('+1 month'));


        for ($j = 1; $j <= $nexttotaldays; $j++) {

            $day = date('w', strtotime("$nextYear-$nextMonth-$j"));


            if (in_array($day, $week_days))
                $data['next_month'][] = ['day' => date('l', strtotime("$nextYear-$nextMonth-$j")), 'date' => date('Y-m-d', strtotime("$nextYear-$nextMonth-$j")), 'week_day' => $day + 1, "is_available" => 1 ];
            else
                $data['next_month'][] = ['day' => date('l', strtotime("$nextYear-$nextMonth-$j")), 'date' => date('Y-m-d', strtotime("$nextYear-$nextMonth-$j")), 'week_day' => $day + 1, "is_available" => 0];

        }

        return $data;


    }
}