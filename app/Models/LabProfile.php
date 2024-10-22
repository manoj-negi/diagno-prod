<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabProfile extends Model
{
    use HasFactory;
    protected $table = 'lab_profile';
    protected $primaryKey = 'id';

    // Add TAT and no_of_parameter to the fillable array
    protected $fillable = [
        'title',
        'lab_id',
        'profile_name',
        'description',
        'amount',
        'image',
        'TAT',  // Newly added column
        'no_of_parameter',  // Newly added column
        'updated_at',
        'created_at',
    ];

    public function hospital()
    {
        return $this->hasOne(User::class, 'id', 'lab_id');
    }

    public function tests()
    {
        return $this->belongsToMany(LabTest::class, 'lab_profile_tests', 'profile_id', 'test_id');
    }

    public function test()
    {
        return $this->hasOne(LabProfileTests::class, 'profile_id', 'id');
    }

    public function lab()
    {
        return $this->belongsTo(User::class, 'lab_id');
    }

    public function labsTestsProfiles()
    {
        return $this->hasMany(LabsTestsProfile::class, 'lab_profile_id', 'id');
    }
    public function packages()
{
    return $this->belongsToMany(Package::class, 'labs_profile_packages', 'lab_profile_id', 'package_id');
}

}
