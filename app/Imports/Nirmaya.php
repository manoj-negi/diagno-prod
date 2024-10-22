<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class Nirmaya implements ToCollection, WithHeadingRow
{
    private $labId;

    public function __construct($labId)
    {
        $this->labId = $labId;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            if (isset($row['type']) && strtolower($row['type']) === 'profile') {
                // Save to lab_profile table
                LabProfile::create([
                    'title' => $row['Test & Parameters'],
                ]);
            } else {
                // Save to lab_tests table
                LabTest::create([
                    'test_name' => $row['Test & Parameters'],
                    'amount'    => $row['Price'],
                ]);
            }
        }
    }
}