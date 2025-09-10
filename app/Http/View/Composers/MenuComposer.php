<?php
namespace App\Http\View\Composers;

use App\Models\Menu;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view)
    {
        $user = auth()->user();

        if (!$user) 
        {
            $view->with('menus', collect());
            return;
        }

        $userPermissions = $user->getAllPermissions()->pluck('name');

        $menus = Menu::query()
            ->whereNull('parent_id') // ดึงเฉพาะเมนูหลัก
            ->where(function ($query) use ($userPermissions) {
                $query->whereNull('permission_name') // เมนูที่เห็นได้ทุกคน
                      ->orWhereIn('permission_name', $userPermissions); // หรือเมนูที่เรามีสิทธิ์
            })
            ->with(['children' => function ($query) use ($userPermissions) {
                // ดึงเมนูย่อยที่ User มีสิทธิ์เห็น
                $query->where(function ($subQuery) use ($userPermissions) {
                    $subQuery->whereNull('permission_name')
                             ->orWhereIn('permission_name', $userPermissions);
                })->orderBy('sequence');
            }])
            ->orderBy('sequence')
            ->get();

        $view->with('menus', $menus);
    }
}