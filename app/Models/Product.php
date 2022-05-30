<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'stock',
        'product_category_id',
        'price',
    ];

    public function category(){
        return $this->hasOne(ProductCategory::class);
    }

    public function order(){
        return $this->hasOne(Order::class);
    }
}
