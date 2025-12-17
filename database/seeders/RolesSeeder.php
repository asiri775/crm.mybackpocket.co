<?php

namespace Database\Seeders;

use App\Helpers\Constants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultLocale = $parameters['locale'] ?? config('app.locale');

        if (!DB::table('roles')->where('id', Constants::ADMIN_ROLE)->exists()) {
            DB::table('roles')->insert([
                'id' => Constants::ADMIN_ROLE,
                'name' => trans('installer::app.seeders.user.role.administrator', [], $defaultLocale),
                'description' => trans('installer::app.seeders.user.role.administrator-role', [], $defaultLocale),
                'permission_type' => 'all',
            ]);
        }

        if (!DB::table('roles')->where('id', Constants::HOST_ROLE)->exists()) {
            DB::table('roles')->insert([
                'id' => Constants::HOST_ROLE,
                'name' => 'Host',
                'description' => 'Host Role',
                'permission_type' => 'all',
            ]);
        }

        if (!DB::table('roles')->where('id', Constants::GUEST_ROLE)->exists()) {
            DB::table('roles')->insert([
                'id' => Constants::GUEST_ROLE,
                'name' => 'Guest',
                'description' => 'Guest Role',
                'permission_type' => 'all',
            ]);
        }

    }

}
