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
            $data = Product::latest()->with('product_category');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function ($product) {

                    return $product->product_category->name;
                })
                ->addColumn('price', function ($product) {

                    return $product->price . '€';
                })
                ->addColumn('action', function ($product) {
                    return view('admin.products.buttons', ['product' => $product]);
                })
                ->rawColumns(['action'])
                ->blacklist(['action'])
                ->make(true);
        }
    }

    public function formCreateProduct()
    {

        $categories = ProductCategory::latest()->orderBy('id')->get();

        return view('admin.products.form')->withCategories($categories)->withForm('Añadir');
    }

    public function formEditProduct(Product $product)
    {
        $categories = ProductCategory::latest()->orderBy('id')->get();
        return view('admin.products.form')->withCategories($categories)->withForm('Editar')->withProduct($product);
    }

    public function createProduct(StoreProductRequest $request)
    {

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'stock' => $request->stock,
            'product_category_id' => $request->category,
            'price' => $request->price,
        ]);

        return redirect()->route('products')->withTitle('Producto añadido');
    }

    public function editProduct(StoreProductRequest $request)
    {

        $product = Product::query()->find($request->id);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->stock = $request->stock;
        $product->product_category_id = $request->category;
        $product->price = $request->price;

        $product->save();

        return redirect()->route('products')->withTitle('Producto editado');

    }

    public function deleteProduct(Request $request)
    {
        $product = Product::query()->find(json_decode($request->product)->id);
        $product->delete();
    }

    public function showProduct(Product $product)
    {
        return view('all.product_view', ['product' => $product]);
    }
}
