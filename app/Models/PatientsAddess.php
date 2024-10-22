<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientsAddess extends Model
{
    use HasFactory;
    protected $table = 'patient_address';
    protected $primarykey = 'id';
    protected $fillable = [
        'user_id',
        'address',
        'latitude',
        'longitude',
        'house_no',
        'near_landmark',
        'area',
        'pin_code',
        'state_id',
        'city_id',
        'address_type',
    ];
    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }
}