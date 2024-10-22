<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalDoctor extends Model
{
    use HasFactory;

   
    protected $table = "hospital_doctors";
    protected $primarykey = 'id';
    protected $fillable = [
        'hospital_id',
        'doctor_name',
        'doctor_age',
        'doctor_category',
        'image',
        'created_at',
        'updated_at',
    ];

  public function hospital()
    {
        return $this->hasOne(User::class, 'id', 'hospital_id');
    }
    public function hospitalCategory()
    {
        return $this->hasOne(HospitalCategory::class, 'id', 'doctor_category');
    }
   // use mutator
    public function getHospitalIdAttribute($value)
    {
        return ucfirst($value);
    }
    public function getDoctorNameAttribute($value)
    {
        return ucfirst($value);
    }
    public function getDoctorAgeAttribute($value)
    {
        return ucfirst($value);
    }
    public function getDoctorCategoryAttribute($value)
    {
        return ucfirst($value);
    }
   
   
}