<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabSelectedPackages extends Model
{
    use HasFactory;
    protected $table='lab_selected_packages';
    protected $primarykey='id';
    protected $fillable=[
        'package_id',
        'lab_id',
        'amount',
        'is_selected',
    ];
    public function packageData()
    {
        return $this->hasOne(Package::class, 'id', 'package_id');
    }
    public function labDetails()
    {
        return $this->hasOne(User::class, 'id', 'lab_id');
    }
   
  
}