<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function getProducts(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::query()->with('product_category')->orderBy('id')->get();
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

        $categories = ProductCategory::query()->orderBy('id')->get();

        return view('admin.products.form')->withCategories($categories)->withForm('Añadir');
    }

    public function formEditProduct(Product $product)
    {
        $categories = ProductCategory::latest()->orderBy('id')->get();
        return view('admin.products.form')->withCategories($categories)->withForm('Editar')->withProduct($product);
    }

    public function createProduct(StoreProductRequest $request)
    {

        $product = Product::factory()->create([
            'name' => $request->name,
            'description' => $request->description,
            'stock' => $request->stock,
            'product_category_id' => $request->category,
            'price' => $request->price,
        ]);

        if ($request->image !== null) {
            $product->addMedia($request->image)
                ->preservingOriginal()
                ->toMediaCollection();
        }

        return redirect()->route('products')->with('done', 'Producto añadido');
    }

    public function editProduct(EditProductRequest $request)
    {

        $product = Product::query()->find($request->id)->first();

        $product->name = $request->name;
        $product->description = $request->description;
        $product->stock = $request->stock;
        $product->product_category_id = $request->category;
        $product->price = $request->price;
        if ($request->image !== null) {
            $product->getMedia()[0]->delete();
            $product
                ->addMedia($request->image)
                ->preservingOriginal()
                ->toMediaCollection();

        }

        $product->save();

        return redirect()->route('products')->with('done', 'Producto editado');

    }

    public function deleteProduct(Request $request)
    {
        $product = Product::query()->find(json_decode($request->product)->id);
        $product->delete();
    }

    function showProduct(Product $product)
    {

        return view('all.products.products_view', ['product' => $product]);

    }

}
