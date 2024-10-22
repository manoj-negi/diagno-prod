<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class settings extends Model
{
    use HasFactory;
    protected $table = "settings";
    protected $primaryKey = "id";

    protected $fillable = [

        'country',
        'key',
        'name',
        'city',
        'favicon',
        'url',
        'state',
        'number',
        'pincode',
        'email',
        'address',
        'value',
    ];
}
