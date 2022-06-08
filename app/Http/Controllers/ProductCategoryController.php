<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreProductCategoryRequest;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{

    public function createCategory(StoreProductCategoryRequest $request){

        $product = ProductCategory::factory()->create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

    return "<option value='$product->id' name='$product->id'>$product->name</option>";
    }

}
