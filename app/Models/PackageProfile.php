<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageProfile extends Model
{
    use HasFactory;
    protected $table='packages_profiles';
    protected $primarykey='id';
    protected $fillable=[
        'profile_id',
        'package_id',
       
    ];
    public function profile_test()
    {
        return $this->hasOne(LabTest::class, 'id', 'test_id');
    }
   
  
}