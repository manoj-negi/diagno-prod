<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabsSeeder extends Seeder
{
    public function run()
    {
        DB::table('labs')->insert([
            ['id' => 1, 'name' => 'Diagno mitra',  'address' => 'delhi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Healthians', 'address' => 'delhi','created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Redcliff', 'address' => 'delhi','created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Nirmaya Lab','address' => 'delhi', 'created_at' => now(), 'updated_at' => now()],
            // Add more labs as needed
        ]);
    }
}
