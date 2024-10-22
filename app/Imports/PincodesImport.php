<?php

namespace App\Imports;

use App\Models\Pincode;
use Maatwebsite\Excel\Concerns\ToModel;

class PincodesImport implements ToModel
{
    public function model(array $row)
    {
        return new Pincode([
            'pincode' => $row[0], // Assuming the first column contains the pincode
        ]);
    }
}
