<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder {
    public function run() {
        $roles = [
            [
                'id'    => 2,
                'title' => 'Entry Person',
            ],
            [
                'id'    => 3,
                'title' => 'Approver',
            ],
            [
                'id'    => 1,
                'title' => 'Admin',
            ],
        ];

        Role::insert( $roles );
    }
}
