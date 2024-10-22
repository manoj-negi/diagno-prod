<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPrescription extends Model
{
    use HasFactory;
    protected $table = 'user_prescription';
    protected $primarykey = 'id';
    protected $fillable = [
        'user_id',
        'prescription_file',
        'prescription_title',
    ];
}