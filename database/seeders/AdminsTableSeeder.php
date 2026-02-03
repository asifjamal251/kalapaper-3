<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            [
                'id' => 1,
                'role_id' => 1,
                'name' => 'AR Technology',
                'username' => 'artechnology',
                'email' => 'info@artechnology.in',
                'password' => Hash::make('ARTech@123'),
                'plain_password' => 'ARTech@123',
                'remember_token' => NULL,
                'avatar' => NULL,
                'status_id' => 14,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => NULL,
            ],
            [
                'id' => 2,
                'role_id' => 2,
                'name' => 'AR Admin',
                'username' => 'aradmin',
                'email' => 'admin@artechnology.in',
                'password' => Hash::make('ARTech@123'),
                'plain_password' => 'ARTech@123',
                'remember_token' => NULL,
                'avatar' => NULL,
                'status_id' => 14,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => NULL,
            ],
        ]);
    }
}
