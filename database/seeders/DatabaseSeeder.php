<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\BankAccountsTableSeeder as SeedersBankAccountsTableSeeder;
use Database\Seeders\GuestTypesTableSeeder as SeedersGuestTypesTableSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,    
            UsersTableSeeder::class,
            SeedersBankAccountsTableSeeder::class,
            SeedersGuestTypesTableSeeder::class,
            DestinationsTableSeeder::class,
        ]);

    }
}
