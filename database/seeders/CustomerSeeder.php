<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // หา User คนแรกมาเป็นเจ้าของข้อมูล (ป้องกัน error เพราะรันผ่าน CLI ไม่มี Auth::user())
        $adminUser = User::first()->id ?? null;

        $filePath = database_path('csv/customers.csv');

        if (!File::exists($filePath)) {
            $this->command->error("File not found: $filePath");
            return;
        }

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file); // อ่านบรรทัดแรก (Header) ข้ามไป

        while (($data = fgetcsv($file)) !== false) {
            // Mapping ข้อมูลจาก CSV (index ตามลำดับในไฟล์)
            // 0: รหัสลูกค้า, 1: ชื่อลูกค้า, 2: เบอร์, 3: ละติจูด, 4: ลองติจูด, 5: LINE, 6: หมายเหตุ

            // ข้ามแถวที่ไม่มีชื่อลูกค้า
            if (empty($data[1])) continue;

            Customer::create([
                'code'       => $data[0] ?: null, // ถ้าว่างให้เป็น null
                'name'       => $data[1],
                'phone'      => $data[2] ?: null,
                'latitude'   => is_numeric($data[3]) ? $data[3] : null,
                'longitude'  => is_numeric($data[4]) ? $data[4] : null,
                'line_id'    => $data[5] ?: null,
                'notes'      => $data[6] ?: null,
                'type'       => 'general', // ค่า Default
                'created_by' => $adminUser,
                'updated_by' => $adminUser,
            ]);
        }

        fclose($file);
        $this->command->info('Customers imported successfully!');
    }
}
