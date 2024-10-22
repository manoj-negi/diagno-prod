<?php

namespace App\Models;

use App\Models\Doctor_hospital;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Avaliablity;


class Hospital extends Model
{
    use HasFactory;
    protected $table = "hospitals";
    protected $id = "id";

    protected $fillable = [
        'name',
        'spenish_name',
        'address',
        'spenish_address',
        'city',
        'spenish_city',
        'description',
        'spenish_description',
        'is_approved',
        'patient_report',
        'hospital_logo',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'doctor_hospital', 'hospital_id', 'doctor_id');
    }
    public function availability()
    {
        return $this->hasMany(Avaliablity::class, 'hospital_id', 'id');
    }


}