<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use function PHPUnit\Framework\returnArgument;

class ProductController extends Controller
{
    public function getProducts(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::query()->join('product_categories', 'product_category_id', 'product_categories.id')->select('products.*', 'product_categories.name as category')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('admin.products.buttons');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function formCreateProduct()
    {

        $categories = ProductCategory::latest()->orderBy('name')->get();

        return view('admin.products.form')->withCategories($categories);
    }

    public function createProduct(StoreProductRequest $request)
    {

        $newProduct = Product::create([
            'name' => $request->name,
            'description' =>$request->description,
            'stock' => $request->stock,
            'price' => $request->price,
            'product_category_id' => $request->category,
        ]);

        return redirect()->route('products')->withTitle('Producto añadido');
    }
}
