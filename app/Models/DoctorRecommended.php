<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorRecommended extends Model
{
    use HasFactory;
    protected $table = "doctor_recommended";
    protected $id = "id";

    protected $fillable = [
        'name',
        'image',
        'designation',
        'review',
        'rating',
        'status',
    ];
    
}