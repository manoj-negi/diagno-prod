<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Doctor_hospital extends Model
{
    use HasFactory;
    protected $table = "doctor_hospital";
    protected $primaryKey = "id";


    
    protected $fillable=[

      'doctor_id',
      'hospital_id', 

    ];
    
    public function getDoctorDetails(){
        
      return $this->hasOne(User::class,'id','doctor_id');
    }


}
