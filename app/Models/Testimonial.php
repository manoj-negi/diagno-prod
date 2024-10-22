<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    protected $table = "testimonials";
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