<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    public function getOrders(Request $request)
    {
        if ($request->ajax()) {
                $data = Order::latest()->with(['user', 'product', 'address']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function () {
                    return view('admin.orders.buttons');
                })
                ->addColumn('user', function ($order) {
                    return $order->user->name;
                })
                ->addColumn('product', function ($order) {
                    return $order->product->name;
                })
                ->addColumn('address', function ($order) {
                    return $order->address->name;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

}
