<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTestName extends Model
{
    use HasFactory;

    protected $table = 'lab_test_name';

    protected $fillable = [
        'lab_id',
        'test_id',
        'amount',
        'description',
    ];

    public function labTest()
    {
        return $this->belongsTo(LabTest::class, 'test_id');
    }

    public function lab()
    {
        return $this->belongsTo(User::class, 'lab_id');
    }
    public function labTests()
    {
        return $this->hasMany(LabTest::class);
    }
    public function test()
    {
        return $this->belongsTo(LabTest::class, 'test_id', 'id');
    }
}
