<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_permissions')->insert([
            ['role_id' => 1, 'permission_key' => 'add_admin'],
            ['role_id' => 1, 'permission_key' => 'add_bread'],
            ['role_id' => 1, 'permission_key' => 'add_app_setting'],
            ['role_id' => 1, 'permission_key' => 'add_media'],
            ['role_id' => 1, 'permission_key' => 'add_menu'],
            ['role_id' => 1, 'permission_key' => 'add_role'],
            ['role_id' => 1, 'permission_key' => 'add_country'],
            ['role_id' => 1, 'permission_key' => 'add_state'],
            ['role_id' => 1, 'permission_key' => 'add_district'],
            ['role_id' => 1, 'permission_key' => 'add_city'],
            ['role_id' => 1, 'permission_key' => 'browse_admin'],
            ['role_id' => 1, 'permission_key' => 'browse_bread'],
            ['role_id' => 1, 'permission_key' => 'browse_dashboard'],
            ['role_id' => 1, 'permission_key' => 'browse_media'],
            ['role_id' => 1, 'permission_key' => 'browse_menu'],
            ['role_id' => 1, 'permission_key' => 'browse_role'],
            ['role_id' => 1, 'permission_key' => 'browse_setting'],
            ['role_id' => 1, 'permission_key' => 'browse_app_setting'],
            ['role_id' => 1, 'permission_key' => 'browse_access_control'],
            ['role_id' => 1, 'permission_key' => 'browse_country'],
            ['role_id' => 1, 'permission_key' => 'browse_state'],
            ['role_id' => 1, 'permission_key' => 'browse_district'],
            ['role_id' => 1, 'permission_key' => 'browse_city'],
            ['role_id' => 1, 'permission_key' => 'delete_admin'],
            ['role_id' => 1, 'permission_key' => 'delete_bread'],
            ['role_id' => 1, 'permission_key' => 'delete_media'],
            ['role_id' => 1, 'permission_key' => 'delete_menu'],
            ['role_id' => 1, 'permission_key' => 'delete_role'],
            ['role_id' => 1, 'permission_key' => 'delete_app_setting'],
            ['role_id' => 1, 'permission_key' => 'delete_country'],
            ['role_id' => 1, 'permission_key' => 'delete_state'],
            ['role_id' => 1, 'permission_key' => 'delete_district'],
            ['role_id' => 1, 'permission_key' => 'delete_city'],
            ['role_id' => 1, 'permission_key' => 'edit_admin'],
            ['role_id' => 1, 'permission_key' => 'edit_bread'],
            ['role_id' => 1, 'permission_key' => 'edit_media'],
            ['role_id' => 1, 'permission_key' => 'edit_menu'],
            ['role_id' => 1, 'permission_key' => 'edit_role'],
            ['role_id' => 1, 'permission_key' => 'edit_app_setting'],
            ['role_id' => 1, 'permission_key' => 'edit_country'],
            ['role_id' => 1, 'permission_key' => 'edit_state'],
            ['role_id' => 1, 'permission_key' => 'edit_district'],
            ['role_id' => 1, 'permission_key' => 'edit_city'],
            ['role_id' => 1, 'permission_key' => 'logo_app_setting'],
            ['role_id' => 1, 'permission_key' => 'read_admin'],
            ['role_id' => 1, 'permission_key' => 'read_bread'],
            ['role_id' => 1, 'permission_key' => 'read_media'],
            ['role_id' => 1, 'permission_key' => 'read_menu'],
            ['role_id' => 1, 'permission_key' => 'read_role'],
            ['role_id' => 1, 'permission_key' => 'read_app_setting'],
            ['role_id' => 2, 'permission_key' => 'browse_admin'],
            ['role_id' => 2, 'permission_key' => 'browse_dashboard'],
            ['role_id' => 2, 'permission_key' => 'browse_setting'],
            ['role_id' => 2, 'permission_key' => 'edit_admin'],
            ['role_id' => 2, 'permission_key' => 'read_admin'],
            ['role_id' => 3, 'permission_key' => 'browse_dashboard'],
            ['role_id' => 4, 'permission_key' => 'browse_dashboard']
        ]);
    }
}
