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
                'username' => 'superadmin', // Added username field
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
                'created_by' => 1,

            ],

            // Administrators
            [
                'id' => 2,
                'username' => 'admin1', // Added username field
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
                'created_by' => 1,

            ],
            [
                'id' => 3,
                'username' => 'admin2', // Added username field
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
                'created_by' => 1,

            ],
            [
                'id' => 4,
                'username' => 'admin3', // Added username field
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
                'created_by' => 1,

            ],

            // Operators under Administrator 1
            [
                'id' => 5,
                'username' => 'operator1', // Added username field
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
                'created_by' => 1,

            ],
            [
                'id' => 6,
                'username' => 'operator2', // Added username field
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
                'created_by' => 1,

            ],

            // Operators under Administrator 2
            [
                'id' => 7,
                'username' => 'operator3', // Added username field
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
                'created_by' => 1,

            ],
            [
                'id' => 8,
                'username' => 'operator4', // Added username field
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
                'created_by' => 1,

            ],

            // Operators under Administrator 3
            [
                'id' => 9,
                'username' => 'operator5', // Added username field
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
                'created_by' => 1,

            ],
            [
                'id' => 10,
                'username' => 'operator6', // Added username field
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
                'created_by' => 1,

            ],
        ]);
    }
}
