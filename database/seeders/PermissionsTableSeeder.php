<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_access',
            ],
            [
                'id'    => 8,
                'title' => 'user_create',
            ],
            [
                'id'    => 9,
                'title' => 'user_edit',
            ],
            [
                'id'    => 10,
                'title' => 'user_show',
            ],
            [
                'id'    => 11,
                'title' => 'user_delete',
            ],
            [
                'id'    => 12,
                'title' => 'user_access',
            ],
            [
                'id'    => 13,
                'title' => 'country_create',
            ],
            [
                'id'    => 14,
                'title' => 'country_edit',
            ],
            [
                'id'    => 15,
                'title' => 'country_show',
            ],
            [
                'id'    => 16,
                'title' => 'country_delete',
            ],
            [
                'id'    => 17,
                'title' => 'country_access',
            ],
            [
                'id'    => 18,
                'title' => 'bank_create',
            ],
            [
                'id'    => 19,
                'title' => 'bank_edit',
            ],
            [
                'id'    => 20,
                'title' => 'bank_show',
            ],
            [
                'id'    => 21,
                'title' => 'bank_delete',
            ],
            [
                'id'    => 22,
                'title' => 'bank_access',
            ],
            [
                'id'    => 23,
                'title' => 'transaction_create',
            ],
            [
                'id'    => 24,
                'title' => 'transaction_edit',
            ],
            [
                'id'    => 25,
                'title' => 'transaction_show',
            ],
            [
                'id'    => 26,
                'title' => 'transaction_delete',
            ],
            [
                'id'    => 27,
                'title' => 'transaction_access',
            ],
            [
                'id'    => 28,
                'title' => 'group_create',
            ],
            [
                'id'    => 29,
                'title' => 'group_edit',
            ],
            [
                'id'    => 30,
                'title' => 'group_show',
            ],
            [
                'id'    => 31,
                'title' => 'group_delete',
            ],
            [
                'id'    => 32,
                'title' => 'group_access',
            ],
            [
                'id'    => 33,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
