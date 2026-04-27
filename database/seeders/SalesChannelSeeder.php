<?php

namespace Database\Seeders;

use App\Models\SalesChannel;
use Illuminate\Database\Seeder;

class SalesChannelSeeder extends Seeder
{
    public function run(): void
    {
        $channels = [
            ['name' => 'หน้าร้าน (Walk-in)', 'is_active' => true],
            ['name' => 'Facebook Inbox', 'is_active' => true],
            ['name' => 'Line Official', 'is_active' => true],
            ['name' => 'Shopee', 'is_active' => true],
            ['name' => 'Lazada', 'is_active' => true],
            ['name' => 'TikTok Shop', 'is_active' => true],
            ['name' => 'Instagram DM', 'is_active' => true],
            ['name' => 'Website', 'is_active' => false],
            ['name' => 'Telegram', 'is_active' => false],
        ];

        foreach ($channels as $channel) {
            SalesChannel::firstOrCreate(['name' => $channel['name']], $channel);
        }
    }
}