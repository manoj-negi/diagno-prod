<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalCategory extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'hospital_category';
    protected $fillable = ['image', 'title', 'description'];



 public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }
    public function getDescriptionAttribute($value)
    {
        return ucfirst($value);
    }
}