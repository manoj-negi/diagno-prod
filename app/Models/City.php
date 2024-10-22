<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\State;

class City extends Model
{
    use HasFactory;
    protected $table = 'cities';
    protected $fillable = ['city', 'state_id'];


    public function city()
    {
        return $this->hasOne(State::class, 'state_id', 'id');
    }
}