<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole(['admin', 'super_admin'])) {
                return view('admin.home');
            } else if (Auth::check()) {
                return view('client.home');
            }
        }

        return view('home');

    }

    public function getEchartData()
    {
        $orders = Order::with(['product'])->get();
        $productsByCategory = [];
        foreach ($orders as $order) {
            foreach ($order->product as $product) {
                isset($productsByCategory["" . $product->product_category->name]) ?
                    $productsByCategory["" . $product->product_category->name]++ :
                    $productsByCategory["" . $product->product_category->name] = 1;
            }
        }
        $echartData = [];

        foreach ($productsByCategory as $index => $value) {
            $echartData[] = ['value' => $value, 'name' => $index];
        }
        return $echartData;
    }
}
