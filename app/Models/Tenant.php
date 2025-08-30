<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'status',
        'logo_path',
        'receipt_header_text',
        'receipt_footer_text',
    ];

    //1 Tenant มีได้หลาย Branch
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
