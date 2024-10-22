<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    use HasFactory;

    protected $table = 'labs_tests';

    protected $fillable = [
        'test_name',
    ];

    public function labTestNames()
    {
        return $this->hasMany(LabTestName::class, 'test_id');
    }
    public function labTestName()
    {
        return $this->belongsTo(LabTestName::class, 'lab_test_name_id');

        // return $this->belongsTo(LabTestName::class); // Adjust this according to your actual relationship
    }
}