<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'last_name' => 'Doe',
            'email' => 'admin@invisofts.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456789'), // Encrypt the password
            'wallet' => 100.00,
            'postal_code' => '123456',
            'gst' => 'GST123456',
            'number' => '1234567890',
            'refer_code' => Str::random(10),
            'age' => '30',
            'sex' => 'male',
            'status' => 1,
            'is_profile' => 1,
            'profile_image' => 'default.jpg',
            'address' => '123 Main St',
            'dob' => '1990-01-01',
            'is_approved' => 'approved',
            'otp' => '123456',
            'home_collection' => 'yes',
            'otp_expire' => now()->addMinutes(10)->timestamp,
            'plan_id' => 1,
            'plan_start_date' => now(),
            'plan_expire_date' => now()->addYear(),
            'fcm_token' => Str::random(64),
            'remember_token' => Str::random(10),
            'city_id' => '1',
            'state_id' => 1,
            'hospital_category' => 1,
            'is_hospital' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'is_patient' => 1,
            'hospital_logo' => 'logo.jpg',
            'hospital_description' => 'Hospital description goes here',
        ]);
    }
}
