<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabsAvailability extends Model
{
    use HasFactory;
    protected $table='labs_availability';
    protected $primarykey='id';
    protected $fillable=[
        'lab_id',
        'week_day',
        'start_time',
        'end_time',
        'status',
    ];

}
