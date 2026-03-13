<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    use Auditable; // 💡 สถาปัตยกรรมใหม่: ดึง Audit Trail มาใช้บรรทัดเดียวจบ!

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'phone',
        'tax_id',
        'branch_code',
        'logo_path',
        'settings',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'settings'  => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * 💡 Helper Method: ดึงค่า Setting ย่อยออกมาอย่างปลอดภัย
     * ใส่ Type Hint mixed เพื่อรองรับ PHP 8
     */
    public function getSetting(string $key, mixed $default = null): mixed
    {
        return $this->settings[$key] ?? $default;
    }
}
