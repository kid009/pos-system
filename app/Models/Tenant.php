<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    //1 Tenant มีได้หลาย Branch
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
