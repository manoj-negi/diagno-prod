<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Medicaltests;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Symptom;
use App\Models\Disease;


class Speciality extends Model
{
    use HasFactory;
    protected $table = 'specialities';
    protected $primarykey = 'id';
    protected $fillable = [
        'name',
        'spenish_name',
        'description',
        'spenish_description',
        'status'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function diseases()
    {
        return $this->belongsToMany(Disease::class)->where('status', 1);
    }
    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class)->where('status', 1);
    }
}