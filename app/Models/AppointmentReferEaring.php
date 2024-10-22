<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AppointmentReferEaring extends Model
{
    use HasFactory; protected $table='appointment_refer_earing';

    protected $fillable=[
        'appointment_id',
        'refer_code',
        'amount',
      
      
    ];
  
    public function AppointmentReferEaring()
    {
        return $this->hasOne(User::class, 'refer_code', 'refer_code');
    }
    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'id', 'appointment_id');
    }

   
}

