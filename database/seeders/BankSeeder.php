<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            [
                'name' => 'ธนาคารกสิกรไทย',
                'code' => 'KBANK',
                'account_name' => 'บริษัท พีเจเอส จำกัด',
                'account_no' => '123-4-56789-0',
                'is_active' => true,
            ],
            [
                'name' => 'ธนาคารไทยพาณิชย์',
                'code' => 'SCB',
                'account_name' => 'บริษัท พีเจเอส จำกัด',
                'account_no' => '234-5-67890-1',
                'is_active' => true,
            ],
            [
                'name' => 'ธนาคารกรุงเทพ',
                'code' => 'BBL',
                'account_name' => 'บริษัท พีเจเอส จำกัด',
                'account_no' => '345-6-78901-2',
                'is_active' => true,
            ],
            [
                'name' => 'ธนาคารกรุงไทย',
                'code' => 'KTB',
                'account_name' => 'บริษัท พีเจเอส จำกัด',
                'account_no' => '456-7-89012-3',
                'is_active' => true,
            ],
            [
                'name' => 'ธนาคารทหารไทย',
                'code' => 'TTB',
                'account_name' => 'บริษัท พีเจเอส จำกัด',
                'account_no' => '567-8-90123-4',
                'is_active' => true,
            ],
            [
                'name' => 'ธนาคารออมสิน',
                'code' => 'GSB',
                'account_name' => 'บริษัท พีเจเอส จำกัด',
                'account_no' => '678-9-01234-5',
                'is_active' => false,
            ],
        ];

        foreach ($banks as $bank) {
            Bank::firstOrCreate(['code' => $bank['code']], $bank);
        }
    }
}