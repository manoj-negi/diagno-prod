<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabCities extends Model
{
    use HasFactory;
    protected $table='lab_cities';
    protected $primarykey='id';
    protected $fillable=[
        'lab_id',
        'city',
        'state',
    ];
    public function citys()
    {
        return $this->hasMany(City::class, 'id', 'city');
    }
   

}
