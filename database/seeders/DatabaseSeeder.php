<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure default admin exists
        $email = 'shaheedmhmed@gmail.com';
        $admin = User::where('email', $email)->first();
        if (! $admin) {
            User::create([
                'name' => 'Admin',
                'email' => $email,
                'password' => Hash::make('shaheed/1234'),
                'role' => 'admin',
            ]);
        } else {
            // Ensure role is admin if the user already exists
            if ($admin->role !== 'admin') {
                $admin->role = 'admin';
                $admin->save();
            }
        }
    }
}
