<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'sku', 'title', 'description', 'price', 'quantity', 'category'
    ];

    public function CategoryProduct()
    {
        return $this->hasMany(CategoryProduct::class, 'product_id');
    }

    // public function getTitleAttribute($value)
    // {
    //     return ucfirst($value);
    // }
}
