<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabProfileTests extends Model
{
    use HasFactory;
    protected $table='lab_profile_tests';
    protected $primarykey='id';
    protected $fillable=[
        'profile_id',
        'test_id',
        'updated_at',
        'created_at',
    ];
    public function profile_test()
    {
        return $this->hasOne(LabTest::class, 'id', 'test_id');
    }
   
  
}