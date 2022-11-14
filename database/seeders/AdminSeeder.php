<?php

namespace Database\Seeders;

use App\Common\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Peaceworc Admin',
            'email' => 'admin@peaceworc.com',
            'password' => Hash::make('password'),
            'role' => Role::Admin
        ]);
    }
}
