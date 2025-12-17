<?php

namespace Webkul\Installer\Database\Seeders\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        // DB::table('users')->insert([
        //     'name'            => 'Example Admin',
        //     'email'           => 'admin-crm@myoffice.ca',
        //     'password'        => bcrypt('admin123'),
        //     // 'api_token'       => Str::random(80),
        //     'created_at'      => date('Y-m-d H:i:s'),
        //     'updated_at'      => date('Y-m-d H:i:s'),
        //     'user_status'          => 1,
        //     'role_id'         => 1,
        //     'view_permission' => 'global',
        // ]);
        DB::table('users')->where('id', 1)->update(
            [
                'role_id' => 1,
                'user_status' => 1,
            ]
        );
    }
}
