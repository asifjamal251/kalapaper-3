<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('permissions')->insert([

            ['menu_slug' => 'role','permission_key' => 'browse_role'],
            ['menu_slug' => 'role','permission_key' => 'read_role'],
            ['menu_slug' => 'role','permission_key' => 'add_role'],
            ['menu_slug' => 'role','permission_key' => 'edit_role'],
            ['menu_slug' => 'role','permission_key' => 'delete_role'],

            ['menu_slug' => 'menu','permission_key' => 'browse_menu'],
            ['menu_slug' => 'menu','permission_key' => 'read_menu'],
            ['menu_slug' => 'menu','permission_key' => 'add_menu'],
            ['menu_slug' => 'menu','permission_key' => 'edit_menu'],
            ['menu_slug' => 'menu','permission_key' => 'delete_menu'],

            ['menu_slug' => 'access_control','permission_key' => 'browse_access_control'],
            ['menu_slug' => 'dashboard','permission_key' => 'browse_dashboard'],

            ['menu_slug' => 'bread','permission_key' => 'browse_bread'],
            ['menu_slug' => 'bread','permission_key' => 'read_bread'],
            ['menu_slug' => 'bread','permission_key' => 'add_bread'],
            ['menu_slug' => 'bread','permission_key' => 'edit_bread'],
            ['menu_slug' => 'bread','permission_key' => 'delete_bread'],

            ['menu_slug' => 'app_setting','permission_key' => 'browse_app_setting'],
            ['menu_slug' => 'app_setting','permission_key' => 'read_app_setting'],
            ['menu_slug' => 'app_setting','permission_key' => 'add_app_setting'],
            ['menu_slug' => 'app_setting','permission_key' => 'edit_app_setting'],
            ['menu_slug' => 'app_setting','permission_key' => 'delete_app_setting'],
            ['menu_slug' => 'app_setting','permission_key' => 'logo_app_setting'],

            ['menu_slug' => 'admin','permission_key' => 'browse_admin'],
            ['menu_slug' => 'admin','permission_key' => 'read_admin'],
            ['menu_slug' => 'admin','permission_key' => 'add_admin'],
            ['menu_slug' => 'admin','permission_key' => 'edit_admin'],
            ['menu_slug' => 'admin','permission_key' => 'delete_admin'],
            ['menu_slug' => 'admin','permission_key' => 'show_password_admin'],

            ['menu_slug' => 'media','permission_key' => 'browse_media'],
            ['menu_slug' => 'media','permission_key' => 'read_media'],
            ['menu_slug' => 'media','permission_key' => 'add_media'],
            ['menu_slug' => 'media','permission_key' => 'edit_media'],
            ['menu_slug' => 'media','permission_key' => 'delete_media'],

            ['menu_slug' => 'quality','permission_key' => 'read_quality'],
            ['menu_slug' => 'quality','permission_key' => 'add_quality'],
            ['menu_slug' => 'quality','permission_key' => 'edit_quality'],
            ['menu_slug' => 'quality','permission_key' => 'delete_quality'],

            ['menu_slug' => 'inward','permission_key' => 'browse_inward'],
            ['menu_slug' => 'inward','permission_key' => 'read_inward'],
            ['menu_slug' => 'inward','permission_key' => 'add_inward'],
            ['menu_slug' => 'inward','permission_key' => 'edit_inward'],
            ['menu_slug' => 'inward','permission_key' => 'delete_inward'],

            ['menu_slug' => 'stock','permission_key' => 'browse_stock'],
            ['menu_slug' => 'stock','permission_key' => 'read_stock'],
            ['menu_slug' => 'stock','permission_key' => 'add_stock'],
            ['menu_slug' => 'stock','permission_key' => 'edit_stock'],
            ['menu_slug' => 'stock','permission_key' => 'delete_stock'],

            ['menu_slug' => 'purchase_order','permission_key' => 'browse_purchase_order'],
            ['menu_slug' => 'purchase_order','permission_key' => 'read_purchase_order'],
            ['menu_slug' => 'purchase_order','permission_key' => 'add_purchase_order'],
            ['menu_slug' => 'purchase_order','permission_key' => 'edit_purchase_order'],
            ['menu_slug' => 'purchase_order','permission_key' => 'delete_purchase_order'],

            ['menu_slug' => 'job_card','permission_key' => 'browse_job_card'],
            ['menu_slug' => 'job_card','permission_key' => 'read_job_card'],
            ['menu_slug' => 'job_card','permission_key' => 'add_job_card'],
            ['menu_slug' => 'job_card','permission_key' => 'edit_job_card'],
            ['menu_slug' => 'job_card','permission_key' => 'delete_job_card'],

            ['menu_slug' => 'parties','permission_key' => 'browse_parties'],
            ['menu_slug' => 'parties','permission_key' => 'read_parties'],
            ['menu_slug' => 'parties','permission_key' => 'add_parties'],
            ['menu_slug' => 'parties','permission_key' => 'edit_parties'],

        ]);
    }
}