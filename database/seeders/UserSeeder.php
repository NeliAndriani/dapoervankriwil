<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $userpemilik = User::create([
            'name'=> 'Pemilik',
            'email'=> 'pemilik@website.com',
            'password'=> bcrypt('pemilik123'),
        ]);

        $userpemilik->assignRole('pemilik');

        $userkasir = User::create([
            'name'=> 'Kasir',
            'email'=> 'kasir@website.com',
            'password'=> bcrypt('kasir123'),
        ]);

        $userkasir->assignRole('kasir');

        $userkoki = User::create([
            'name'=> 'Koki',
            'email'=> 'koki@website.com',
            'password'=> bcrypt('koki123'),
        ]);

        $userkoki->assignRole('koki');

        $userstaff_pembelian = User::create([
            'name'=> 'Staff Pembelian',
            'email'=> 'staff_pembelian@website.com',
            'password'=> bcrypt('staffpembelian123'),
        ]);

        $userstaff_pembelian->assignRole('staff_pembelian');
    }
}
