<?php

namespace App\Services;

use App\Models\ProductCategory;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductCategoryService
{
    /**
     * ดึงข้อมูลหมวดหมู่ทั้งหมดแบบแบ่งหน้า
     */
    public function listPaginated(): LengthAwarePaginator
    {
        return ProductCategory::latest()->paginate(10);
    }

    /**
     * สร้างหมวดหมู่ใหม่
     * @param array $data ข้อมูลที่ผ่านการตรวจสอบ (Validated) แล้ว
     * @return ProductCategory
     */
    public function createCategory(array $data): ProductCategory
    {
        // $data มาจาก $request->validated() ซึ่งปลอดภัยแล้ว
        return ProductCategory::create($data);
    }

    /**
     * อัปเดตหมวดหมู่
     * @param array $data ข้อมูลที่ผ่านการตรวจสอบ (Validated) แล้ว
     * @param ProductCategory $category Model ที่ต้องการอัปเดต
     * @return bool
     */
    public function updateCategory(array $data, ProductCategory $category): bool
    {
        return $category->update($data);
    }

    /**
     * ลบหมวดหมู่
     * @param ProductCategory $category Model ที่ต้องการลบ
     * @return bool
     * @throws \Exception
     */
    public function deleteCategory(ProductCategory $category): bool
    {
        try {
            // (ในอนาคต: อาจต้องเพิ่ม logic ตรวจสอบว่ามีสินค้าผูกอยู่หรือไม่ก่อนลบ)
            // if ($category->products()->count() > 0) {
            //     throw new \Exception('ไม่สามารถลบได้ เนื่องจากมีสินค้าในหมวดหมู่นี้');
            // }

            return $category->delete();

        } catch (QueryException $e) {
            // ดักจับ Error ที่มาจาก Database โดยเฉพาะ (เช่น Foreign Key)
            // 23000 คือ SQLSTATE code สำหรับ integrity constraint violation
            if ($e->getCode() == 23000) { 
                throw new \Exception('ไม่สามารถลบหมวดหมู่นี้ได้ เนื่องจากมีสินค้าผูกอยู่');
            }
            // ส่งต่อ Error อื่นๆ
            throw $e;
        }
    }
}