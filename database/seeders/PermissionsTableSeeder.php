<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            array('id' => '1','menu_slug' => 'role','permission_key' => 'browse_role','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '2','menu_slug' => 'role','permission_key' => 'read_role','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '3','menu_slug' => 'role','permission_key' => 'add_role','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '4','menu_slug' => 'role','permission_key' => 'edit_role','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '5','menu_slug' => 'role','permission_key' => 'delete_role','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '6','menu_slug' => 'menu','permission_key' => 'browse_menu','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '7','menu_slug' => 'menu','permission_key' => 'read_menu','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '8','menu_slug' => 'menu','permission_key' => 'add_menu','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '9','menu_slug' => 'menu','permission_key' => 'edit_menu','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '10','menu_slug' => 'menu','permission_key' => 'delete_menu','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '11','menu_slug' => 'access_control','permission_key' => 'browse_access_control','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '12','menu_slug' => 'dashboard','permission_key' => 'browse_dashboard','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '13','menu_slug' => 'bread','permission_key' => 'browse_bread','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '14','menu_slug' => 'bread','permission_key' => 'read_bread','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '15','menu_slug' => 'bread','permission_key' => 'add_bread','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '16','menu_slug' => 'bread','permission_key' => 'edit_bread','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '17','menu_slug' => 'bread','permission_key' => 'delete_bread','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '18','menu_slug' => 'app_setting','permission_key' => 'browse_app_setting','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '19','menu_slug' => 'app_setting','permission_key' => 'read_app_setting','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '20','menu_slug' => 'app_setting','permission_key' => 'add_app_setting','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '21','menu_slug' => 'app_setting','permission_key' => 'edit_app_setting','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '22','menu_slug' => 'app_setting','permission_key' => 'delete_app_setting','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '23','menu_slug' => 'app_setting','permission_key' => 'logo_app_setting','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '24','menu_slug' => 'admin','permission_key' => 'browse_admin','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '25','menu_slug' => 'admin','permission_key' => 'read_admin','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '26','menu_slug' => 'admin','permission_key' => 'add_admin','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '27','menu_slug' => 'admin','permission_key' => 'edit_admin','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '28','menu_slug' => 'admin','permission_key' => 'delete_admin','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '29','menu_slug' => 'admin','permission_key' => 'show_password_admin','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '30','menu_slug' => 'media','permission_key' => 'browse_media','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '31','menu_slug' => 'media','permission_key' => 'read_media','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '32','menu_slug' => 'media','permission_key' => 'add_media','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '33','menu_slug' => 'media','permission_key' => 'edit_media','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '34','menu_slug' => 'media','permission_key' => 'delete_media','created_at' => '2025-04-07 07:06:34','updated_at' => '2025-04-07 07:06:34'),
            array('id' => '35','menu_slug' => 'client','permission_key' => 'browse_client','created_at' => NULL,'updated_at' => NULL),
            array('id' => '36','menu_slug' => 'client','permission_key' => 'read_client','created_at' => NULL,'updated_at' => NULL),
            array('id' => '37','menu_slug' => 'client','permission_key' => 'add_client','created_at' => NULL,'updated_at' => NULL),
            array('id' => '38','menu_slug' => 'client','permission_key' => 'edit_client','created_at' => NULL,'updated_at' => NULL),
            array('id' => '39','menu_slug' => 'client','permission_key' => 'delete_client','created_at' => NULL,'updated_at' => NULL),
            array('id' => '41','menu_slug' => 'quality','permission_key' => 'read_quality','created_at' => NULL,'updated_at' => NULL),
            array('id' => '42','menu_slug' => 'quality','permission_key' => 'add_quality','created_at' => NULL,'updated_at' => NULL),
            array('id' => '43','menu_slug' => 'quality','permission_key' => 'edit_quality','created_at' => NULL,'updated_at' => NULL),
            array('id' => '44','menu_slug' => 'quality','permission_key' => 'delete_quality','created_at' => NULL,'updated_at' => NULL),
            array('id' => '49','menu_slug' => 'inward','permission_key' => 'browse_inward','created_at' => NULL,'updated_at' => NULL),
            array('id' => '50','menu_slug' => 'inward','permission_key' => 'read_inward','created_at' => NULL,'updated_at' => NULL),
            array('id' => '51','menu_slug' => 'inward','permission_key' => 'add_inward','created_at' => NULL,'updated_at' => NULL),
            array('id' => '52','menu_slug' => 'inward','permission_key' => 'edit_inward','created_at' => NULL,'updated_at' => NULL),
            array('id' => '53','menu_slug' => 'inward','permission_key' => 'delete_inward','created_at' => NULL,'updated_at' => NULL),
            array('id' => '55','menu_slug' => 'stock','permission_key' => 'browse_stock','created_at' => NULL,'updated_at' => NULL),
            array('id' => '56','menu_slug' => 'stock','permission_key' => 'read_stock','created_at' => NULL,'updated_at' => NULL),
            array('id' => '57','menu_slug' => 'stock','permission_key' => 'add_stock','created_at' => NULL,'updated_at' => NULL),
            array('id' => '58','menu_slug' => 'stock','permission_key' => 'edit_stock','created_at' => NULL,'updated_at' => NULL),
            array('id' => '59','menu_slug' => 'stock','permission_key' => 'delete_stock','created_at' => NULL,'updated_at' => NULL)
        ]);
}
}
