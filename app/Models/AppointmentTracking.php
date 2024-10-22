<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AppointmentReport;

class AppointmentTracking extends Model
{
    use HasFactory;
    protected $table = 'appointment_tracking';
    protected $primarykey = 'id';
    protected $fillable = [
        'appointment_id',
        'status',
        'created_at',
        'updated_at	',
       

    ];



}