<?php
namespace App\Queries\Category;

use App\Models\Category;
use App\Models\User;

class GetCategoryListQuery
{
    /**
     * ดึงรายการหมวดหมู่ตามสิทธิ์ของ User พร้อมระบบค้นหา
     */
    public function execute(User $user, string $search = '')
    {
        // ใช้ Local Scope (forUser)
        return Category::query()
            ->forUser($user)
            ->with('shop:id,name') // Eager Load ดึงข้อมูลร้านค้าเพื่อลด N+1
            ->when($search, function ($query) use ($search) {
                // ถ้ามีการค้นหา ให้ Filter ตามชื่อ
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->withCount('products') // นับจำนวนสินค้าในหมวดหมู่
            ->orderByDesc('id')
            ->paginate(10);
    }
}
?>
