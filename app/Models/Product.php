<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'stock',
        'product_category_id',
        'price',
    ];

    public function product_category(){
        return $this->belongsTo(ProductCategory::class)->withTrashed();
    }

    public function order(){
        return $this->belongsToMany(Order::class);
    }
}
