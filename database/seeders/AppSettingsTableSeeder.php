<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AppSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('app_settings')->insert([
            [
                'id' => 1,
                'app_name' => 'AR Technology',
                'app_description' => 'Partner with an award-winning app development company to take your brick-and-mortar business online and reach a wider audience with powerful mobile and web solutions.',
                'logo' => NULL,
                'favicon' => NULL,
                'email' => 'asif@artechnology.in',
                'mobile_number' => '+91 9315647380',
                'country_id' => 72,
                'state_id' => 5,
                'district_id' => 164,
                'city_id' => 18809,
                'pincode' => 845412,
                'address' => 'Asif Jamal Shekhi Chakia',
                'created_at' => Carbon::parse('2022-06-26 15:46:11'),
                'updated_at' => Carbon::parse('2024-11-03 08:52:29')
            ],
        ]);
    }
}
