<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Role::create(['name' => 'pemilik', 'guard_name' => 'web'])->givePermissionTo(Permission::all());
        Role::create(['name' => 'kasir', 'guard_name' => 'web'])->givePermissionTo('read_penjualan_menus');
        Role::create(['name' => 'koki', 'guard_name' => 'web']);
        Role::create(['name' => 'staff_pembelian', 'guard_name' => 'web']);
    }
}
