<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('labs_tests')->insert([
            [
                'lab_id' => 1,
                'test_name' => 'Blood Test',
                'amount' => 50.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lab_id' => 2,
                'test_name' => 'X-Ray',
                'amount' => 100.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lab_id' => 3,
                'test_name' => 'MRI Scan',
                'amount' => 300.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
