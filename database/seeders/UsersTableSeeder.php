<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {
    public function run() {
        $users = [
            [
                'id'              => 1,
                'name'            => 'Sajid Javed',
                'email'           => 'sajid@qooglobal.com',
                'password'        => bcrypt( 'password' ),
                'remember_token'  => null,
                'two_factor_code' => '',
            ],
        ];

        User::insert( $users );
    }
}
