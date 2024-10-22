<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            ['key' => 'country', 'value' => 'India'],
            ['key' => 'name', 'value' => 'DiagnoMitra – The future of healthcare'],
            ['key' => 'state', 'value' => 'Rajasthan'],
            ['key' => 'city', 'value' => 'Jaipur'],
            ['key' => 'number', 'value' => '7790980197'],
            ['key' => 'pincode', 'value' => '305001'],
            ['key' => 'email', 'value' => 'yadavnikita182005@gmail.com'],
            ['key' => 'url', 'value' => '#'],
            ['key' => 'address', 'value' => 'Jaipur, Rajasthan 302020, India'],
            ['key' => 'logo', 'value' => '1715460609663fda012790cdlogo.webp'],
            ['key' => 'favicon', 'value' => '1715460757663fda956406cfav.webp'],
            ['key' => 'facebook', 'value' => 'https://facebook.com/'],
            ['key' => 'twitter', 'value' => 'https://twitter.com/'],
            ['key' => 'instagram', 'value' => 'https://instagram.com/'],
            ['key' => 'linkedin', 'value' => 'https://linkdin.com/'],
            ['key' => 'meta_keyword', 'value' => 'DiagnoMitra'],
            ['key' => 'meta_title', 'value' => 'DiagnoMitra – The future of healthcare'],
            ['key' => 'meta_description', 'value' => 'DiagnoMitra – The future of healthcare'],
            ['key' => 'footerconent', 'value' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been.'],
            ['key' => 'footer_logo', 'value' => '1715461360663fdcf02072cdlogo.webp'],
            ['key' => 'spenish_address', 'value' => 'Jaipur, Rajasthan 302020, India'],
            ['key' => 'spenish_footerconent', 'value' => 'Lorem Ipsum es simplemente un texto ficticio de la industria de la impresión y la composición tipográfica. Lorem Ipsum lo ha sido.'],
            ['key' => 'spenish_meta_keyword', 'value' => 'Lorem'],
            ['key' => 'spenish_meta_title', 'value' => 'Lorem'],
            ['key' => 'admin_commission', 'value' => '50'],
            ['key' => 'play_store_url', 'value' => 'www.google.com'],
            ['key' => 'app_store_url', 'value' => 'www.google.com']
        ]);
    }
}
