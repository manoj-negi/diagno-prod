<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AppointmentBill extends Model
{
    use HasFactory; protected $table='appointment_bill';

    protected $fillable=[
        'title',
        'appointment_id',
        'amount',
        'document_file',
      
    ];
   
    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'id', 'appointment_id');
    }
}
