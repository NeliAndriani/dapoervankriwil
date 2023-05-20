<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->generateFor('penjualan_menus', 'web', 'PenjualanMenu');
    }

    public function generateFor($name, $guard, $menu_name)
    {
        Permission::create(['name' => 'read_'.$name, 'guard_name'=> $guard, 'menu_name' => $menu_name]);
        Permission::create(['name' => 'create_'.$name, 'guard_name'=> $guard, 'menu_name' => $menu_name]);
        Permission::create(['name' => 'update_'.$name, 'guard_name'=> $guard, 'menu_name' => $menu_name]);

    }
}
