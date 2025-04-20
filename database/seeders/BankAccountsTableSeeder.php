<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BankAccountsTableSeeder extends Seeder
{
    public function run()
    {
        $bankAccounts = [
            [
                'bank_name' => 'Bank Mandiri',
                'account_name' => 'Dinas terkait di Kabupaten setempat',
                'account_number' => $this->generateAccountNumber(),
                'account_status' => 1,
                'is_public' => 1
            ],
            [
                'bank_name' => 'Bank BRI',
                'account_name' => 'Dinas terkait di Kabupaten setempat',
                'account_number' => $this->generateAccountNumber(),
                'account_status' => 1,
                'is_public' => 1
            ],
            [
                'bank_name' => 'Bank BCA',
                'account_name' => 'Dinas terkait di Kabupaten setempat',
                'account_number' => $this->generateAccountNumber(),
                'account_status' => 1,
                'is_public' => 1
            ],
            [
                'bank_name' => 'Bank BNI',
                'account_name' => 'Dinas terkait di Kabupaten setempat',
                'account_number' => $this->generateAccountNumber(),
                'account_status' => 1,
                'is_public' => 1
            ],
            [
                'bank_name' => 'Bank Danamon',
                'account_name' => 'Dinas terkait di Kabupaten setempat',
                'account_number' => $this->generateAccountNumber(),
                'account_status' => 1,
                'is_public' => 1
            ],
            [
                'bank_name' => 'Bank CIMB Niaga',
                'account_name' => 'Dinas terkait di Kabupaten setempat',
                'account_number' => $this->generateAccountNumber(),
                'account_status' => 1,
                'is_public' => 1
            ],
            [
                'bank_name' => 'Bank Permata',
                'account_name' => 'Dinas terkait di Kabupaten setempat',
                'account_number' => $this->generateAccountNumber(),
                'account_status' => 1,
                'is_public' => 1
            ],
            [
                'bank_name' => 'Bank BTN',
                'account_name' => 'Dinas terkait di Kabupaten setempat',
                'account_number' => $this->generateAccountNumber(),
                'account_status' => 1,
                'is_public' => 1
            ]
        ];

        DB::table('bank_accounts')->insert($bankAccounts);
    }

    private function generateAccountNumber()
    {
        return Str::random(8) . rand(100000, 9999999); // Generates a random 8-13 digit account number
    }
}
