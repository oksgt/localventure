<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            // Super Admin
            [
                'id' => 1, // Super Admin ID set to 1
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'profile_picture' => null, // Since it's not being used
                'parent_id' => null,
                'role_id' => 1, // Super Admin Role
                'remember_token' => Str::random(10),
                'deleted_at' => null, // Soft delete is null initially
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Administrators
            [
                'id' => 2,
                'name' => 'Administrator 1',
                'email' => 'admin1@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'profile_picture' => null,
                'parent_id' => 1, // Reporting to Super Admin
                'role_id' => 2, // Administrator Role
                'remember_token' => Str::random(10),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Administrator 2',
                'email' => 'admin2@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                'profile_picture' => null,
                'parent_id' => 1, // Reporting to Super Admin
                'role_id' => 2, // Administrator Role
                'remember_token' => Str::random(10),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Administrator 3',
                'email' => 'admin3@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '081234567893',
                'profile_picture' => null,
                'parent_id' => 1, // Reporting to Super Admin
                'role_id' => 2, // Administrator Role
                'remember_token' => Str::random(10),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Operators under Administrator 1
            [
                'id' => 5,
                'name' => 'Operator 1',
                'email' => 'operator1@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '082234567890',
                'profile_picture' => null,
                'parent_id' => 2, // Reporting to Administrator 1
                'role_id' => 3, // Operator Role
                'remember_token' => Str::random(10),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Operator 2',
                'email' => 'operator2@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '082234567891',
                'profile_picture' => null,
                'parent_id' => 2, // Reporting to Administrator 1
                'role_id' => 3, // Operator Role
                'remember_token' => Str::random(10),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Operators under Administrator 2
            [
                'id' => 7,
                'name' => 'Operator 3',
                'email' => 'operator3@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '082234567892',
                'profile_picture' => null,
                'parent_id' => 3, // Reporting to Administrator 2
                'role_id' => 3, // Operator Role
                'remember_token' => Str::random(10),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'name' => 'Operator 4',
                'email' => 'operator4@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '082234567893',
                'profile_picture' => null,
                'parent_id' => 3, // Reporting to Administrator 2
                'role_id' => 3, // Operator Role
                'remember_token' => Str::random(10),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Operators under Administrator 3
            [
                'id' => 9,
                'name' => 'Operator 5',
                'email' => 'operator5@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '082234567894',
                'profile_picture' => null,
                'parent_id' => 4, // Reporting to Administrator 3
                'role_id' => 3, // Operator Role
                'remember_token' => Str::random(10),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'name' => 'Operator 6',
                'email' => 'operator6@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '082234567895',
                'profile_picture' => null,
                'parent_id' => 4, // Reporting to Administrator 3
                'role_id' => 3, // Operator Role
                'remember_token' => Str::random(10),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
