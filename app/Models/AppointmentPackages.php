<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentPackages extends Model
{
    use HasFactory;
    protected $table = 'appointment_packages';
    protected $primarykey = 'id';
    protected $fillable = [
        'appointment_id',
        'package_id',
        'package_type',
    ];
}