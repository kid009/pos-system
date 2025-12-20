<?php

namespace App\Traits;

use Schema;
use Illuminate\Support\Facades\Auth;

trait HasUserActivity
{
    protected static function bootHasUserActivity()
    {
        // ทำงานก่อน Create ข้อมูล (creating)
        static::creating(function ($model) {
            // เช็คว่ามี column นี้จริงไหม และมีการ login อยู่ไหม
            if (Auth::check()) {
                if (in_array('created_by', $model->fillable) || Schema::hasColumn($model->getTable(), 'created_by')) {
                    $model->created_by = Auth::id();
                }
                if (in_array('updated_by', $model->fillable) || \Schema::hasColumn($model->getTable(), 'updated_by')) {
                    $model->updated_by = Auth::id();
                }
            }
        });

        // ทำงานก่อน Update ข้อมูล (updating)
        static::updating(function ($model) {
            if (Auth::check()) {
                if (in_array('updated_by', $model->fillable) || \Schema::hasColumn($model->getTable(), 'updated_by')) {
                    $model->updated_by = Auth::id();
                }
            }
        });
    }
}
