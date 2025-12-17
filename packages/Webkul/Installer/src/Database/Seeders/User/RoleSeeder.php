<?php

namespace Webkul\Installer\Database\Seeders\User;

use App\Helpers\Constants;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('roles')->delete();

        $defaultLocale = $parameters['locale'] ?? config('app.locale');

        DB::table('roles')->insert([
            'id' => Constants::ADMIN_ROLE,
            'name' => trans('installer::app.seeders.user.role.administrator', [], $defaultLocale),
            'description' => trans('installer::app.seeders.user.role.administrator-role', [], $defaultLocale),
            'permission_type' => 'all',
        ]);
    }
}
