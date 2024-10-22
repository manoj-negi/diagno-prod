<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabsTestsProfile extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'labs_tests_profiles';

    // Fillable fields for mass assignment
    protected $fillable = [
        'labs_tests_id',
        'lab_profile_id',
        'lab_id',
        'amount',
    ];

    /**
     * Relationship with the Lab model.
     * Each LabsTestsProfile belongs to a Lab.
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id');
    }

    /**
     * Relationship with the LabProfile model.
     * Each LabsTestsProfile belongs to a LabProfile.
     */
    public function labProfile()
    {
        return $this->belongsTo(LabProfile::class, 'lab_profile_id');
    }

    /**
     * Relationship with the LabTest model.
     * Each LabsTestsProfile belongs to a LabTest.
     */
    public function labTest()
    {
        return $this->belongsTo(LabTest::class, 'labs_tests_id');
    }
    public function labsTestsProfiles()
{
    return $this->hasMany(LabsTestsProfile::class, 'lab_profile_id', 'id');
}
public function labTestName()
{
    // Assuming 'test_id' in labs_tests_profile matches 'id' in lab_test_name
    return $this->hasOne(LabTestName::class, 'id', 'labs_tests_id');
}

}
