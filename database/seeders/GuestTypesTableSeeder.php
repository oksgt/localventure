<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestTypesTableSeeder extends Seeder
{
    public function run()
    {
        $guestTypes = [
            ['name' => 'anak-anak'],
            ['name' => 'dewasa'],
            ['name' => 'mancanegara']
        ];

        DB::table('guest_types')->insert($guestTypes);
    }
}
