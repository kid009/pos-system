<?php

namespace App\Models;

use App\Models\Product;
use App\Traits\HasUserActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, HasUserActivity;

    protected $fillable = [
        'name',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [ 'created_by', 'updated_by'];

    // Relations
    public function products() {
        return $this->hasMany(Product::class);
    }
}
