<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'av.sysadmin@alquilavende.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('pass123AV*'),
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assign Admin role
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        $superAdmin = User::firstOrCreate(
            ['email' => 'gmauriciocd@gmail.com'],
            [
                'name' => 'Guido Cordova',
                'password' => Hash::make('pass123AV*'),
                'email_verified_at' => now(),
            ]
        );

        if (!$superAdmin->hasRole('visitor')) {
            $superAdmin->assignRole('visitor');
        }
    }
}
