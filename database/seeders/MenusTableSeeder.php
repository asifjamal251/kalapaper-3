<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            array('slug' => 'access_control','name' => 'Access Control','icon' => 'mdi mdi-tools','parent' => 'control_panel','grand' => NULL,'ordering' => '1','status' => '1','created_at' => '2025-12-14 21:43:38','updated_at' => '2025-12-14 21:43:38'),
  array('slug' => 'admin','name' => 'Admin','icon' => 'mdi mdi-account-lock','parent' => 'master','grand' => NULL,'ordering' => '1','status' => '1','created_at' => '2025-12-14 21:43:38','updated_at' => '2025-12-14 21:43:38'),
  array('slug' => 'app_setting','name' => 'App Setting','icon' => 'bx bx-cog','parent' => 'control_panel','grand' => NULL,'ordering' => '0','status' => '1','created_at' => '2025-12-14 21:43:38','updated_at' => '2025-12-14 21:43:38'),
  array('slug' => 'bread','name' => 'Bread','icon' => 'ft-target','parent' => NULL,'grand' => 'access_control','ordering' => '2','status' => '1','created_at' => '2025-12-14 21:43:38','updated_at' => '2025-12-14 21:43:38'),
  array('slug' => 'client','name' => 'Client','icon' => NULL,'parent' => 'master','grand' => NULL,'ordering' => '0','status' => '1','created_at' => '2025-12-14 21:43:38','updated_at' => '2025-12-14 21:43:38'),
  array('slug' => 'control_panel','name' => 'Control Panel','icon' => 'mdi mdi-tools','parent' => NULL,'grand' => NULL,'ordering' => '2','status' => '1','created_at' => '2025-12-14 21:43:38','updated_at' => '2025-12-14 21:43:38'),
  array('slug' => 'dashboard','name' => 'Dashboard','icon' => 'bx bx-home-circle','parent' => NULL,'grand' => NULL,'ordering' => '0','status' => '1','created_at' => '2025-12-14 21:43:38','updated_at' => '2025-12-14 21:43:38'),
  array('slug' => 'inward','name' => 'Inward','icon' => NULL,'parent' => NULL,'grand' => NULL,'ordering' => NULL,'status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'job_card','name' => 'Job Card','icon' => NULL,'parent' => NULL,'grand' => NULL,'ordering' => NULL,'status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'master','name' => 'Master','icon' => 'ri-apps-2-line','parent' => NULL,'grand' => NULL,'ordering' => '1','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'media','name' => 'Media','icon' => 'bx bx-folder','parent' => NULL,'grand' => NULL,'ordering' => '3','status' => '1','created_at' => '2025-12-14 21:43:38','updated_at' => '2025-12-14 21:43:38'),
  array('slug' => 'menu','name' => 'Menu','icon' => NULL,'parent' => NULL,'grand' => 'access_control','ordering' => '1','status' => '1','created_at' => '2025-12-14 21:43:38','updated_at' => '2025-12-14 21:43:38'),
  array('slug' => 'parties','name' => 'Parties','icon' => NULL,'parent' => NULL,'grand' => NULL,'ordering' => NULL,'status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'purchase_order','name' => 'Purchase Order','icon' => NULL,'parent' => NULL,'grand' => NULL,'ordering' => NULL,'status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'quality','name' => 'Quality','icon' => NULL,'parent' => NULL,'grand' => NULL,'ordering' => NULL,'status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'role','name' => 'Role','icon' => NULL,'parent' => NULL,'grand' => 'access_control','ordering' => '0','status' => '1','created_at' => '2025-12-14 21:43:38','updated_at' => '2025-12-14 21:43:38'),
  array('slug' => 'stock','name' => 'Stock','icon' => NULL,'parent' => NULL,'grand' => NULL,'ordering' => NULL,'status' => '1','created_at' => NULL,'updated_at' => NULL)
        ]);
    }
}
