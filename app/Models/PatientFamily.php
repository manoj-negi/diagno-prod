<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientFamily extends Model
{
    use HasFactory;
    
    protected $table = 'patient_family';
    protected $fillable = [
        'patient_id',
         'hospital_id',
         'name',
         'gender',
         'age',
         'patient_type',
         'email',
         'phone',
         'address',
         'dob',
         'status'
        ];

    public function hospital()
    {
         return $this->hasOne(User::class, 'id', 'hospital_id');
    }
}