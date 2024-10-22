<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentReport extends Model
{
    use HasFactory;

    protected $table = 'appointment_reports';
    protected $fillable = ['patient_id', 'report_image','report_title','appointment_id'];
 

    public function user()
    {
        return $this->hasOne(User::class,'id','patient_id');
    }
}