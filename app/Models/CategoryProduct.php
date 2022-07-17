<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    use HasFactory;

    protected $table = 'category_products';

    protected $fillable = [
        'category_id','product_id'
    ];

    public function Category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
