<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingReview extends Model
{
    use HasFactory;

    protected $table = 'rating_reviews';
    protected $primarykey = 'id';

    protected $appends = ['patient_details'];
    protected $fillable = [
        'patient_id',
        'hospital_id',
        'ratings',
        'review',
        
    ];

    public function getPatientDetailsAttribute()
    {
        $user = User::select('name', 'profile_image')->where('id', $this->patient_id)->first();
        return $user;
    }
   
    public function patient()
    {
        return $this->hasOne(User::class, 'id', 'patient_id');
    }
    public function hospital()
    {
        return $this->hasOne(User::class, 'id', 'hospital_id');
    }

}