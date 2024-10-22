<?php

namespace App\Helper;
use Illuminate\Support\Facades\DB;  // Use DB facade to execute queries

use App\Models\LabCities;
use App\Models\User;

class Helper
{   
    public static function getLabByCities($cityID = '')
    {
       $data = User::whereHas('roles', function($q)
       {
           $q->where('id','=', 4);
       });
       
       if(!empty($cityID)){
            $LabCities = LabCities::where('city',$cityID)->pluck('lab_id')->toArray();
          return  $LabCities = array_values(array_unique($LabCities));

       }
       return $data->pluck('id')->toArray();
    }
    public static function weekDays()
    {
       return [
        '1' => 'Monday',
        '2' => 'Tuesday',
        '3' => 'Wednesday',
        '4' => 'Thursday',
        '5' => 'Friday',
        '6' => 'Saturday',
        '7' => 'Sunday',
       ];
    }
    public static function searchShortKeys($keyWord)
    {
       $keyWord = strtolower($keyWord);
       $data = [
        'cbc' => 'Complete blood count',
        'lft' => 'Liver Function Test',
        'kft' => 'Kidney Function Test',
        'rft' => 'Renal Function Test',
        'tft' => 'Thyroid Function Test',
        'bsr' => 'blood Sugar Random',
        'bsf' => 'Blood Sugar Fasting',
        'hb' => 'HEMOGLOBIN',
        'tsh' => 'Thyroid stimulation Harmone',
       ];

       return $data[$keyWord] ?? $keyWord;

    }
    public static function getLabByPincode($pincodeId)
    {
        // Query labs by pincode_id from the lab_pincode table
        return DB::table('lab_pincode')
            ->where('pincode_id', $pincodeId)
            ->pluck('lab_id')  // Get the lab_id column
            ->toArray();       // Return the result as an array
    }
}