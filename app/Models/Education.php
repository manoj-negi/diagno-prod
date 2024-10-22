<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Docotr;

class Education extends Model
{
    use HasFactory;
    protected $table ='educations';
    protected $primarykey = 'id';
    protected $fillable =[
    'name',
    'spenish_name',
    'description',
    'spenish_description',
    'status'
];
   
}
