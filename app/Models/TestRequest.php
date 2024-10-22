<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestRequest extends Model
{
    use HasFactory;
    protected $table = 'test_request';
    protected $fillable = [
        'patient_name', 
        'age', 
        'lab_id', 
        'payment_status', 
        'test_type',
        'gender',
        'contact',
        'address',
        'amount',
        'updated_at',
        'created_at'
    ];


    public function patient()
    {
         return $this->hasOne(User::class, 'id', 'patient_name');

    }
    public function hospital()
    {
         return $this->hasOne(User::class, 'id', 'lab_id');

    }

 public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }
    public function getDescriptionAttribute($value)
    {
        return ucfirst($value);
    }
}