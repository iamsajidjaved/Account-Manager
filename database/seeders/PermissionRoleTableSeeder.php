<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder {
    public function run() {
        $admin_permissions_arr = [
            1,
            12,
            13,
            14,
            15,
            16,
            22,
            23,
            24,
            25,
            26,
            27,
            28,
            29,
            30,
            31,
            32,
            33,
            34,
            35,
            36,
            37
        ];

        $admin_permissions = Permission::whereIn( 'id', $admin_permissions_arr );
        Role::findOrFail( 1 )->permissions()->sync( $admin_permissions->pluck( 'id' ) );

        $entry_person_permissions_arr = [
            24,
            27,
            28,
            29,
            30,
            31,
            26
        ];

        $user_permissions = Permission::whereIn( 'id', $entry_person_permissions_arr );
        Role::findOrFail( 2 )->permissions()->sync( $user_permissions->pluck( 'id' ) );

        $approver_permissions_arr = [
            24,
            28,
            29,
            31,
            26
        ];

        $approver_permissions = Permission::whereIn( 'id', $approver_permissions_arr );
        Role::findOrFail( 3 )->permissions()->sync( $approver_permissions->pluck( 'id' ) );
    }
}
