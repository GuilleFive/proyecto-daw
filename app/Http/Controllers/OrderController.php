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
            $data = Order::query()->with(['user', 'product', 'address'])->orderBy('id');

            if (isset($request->startDate)) {
                $data = $data->where('order_date', '>=', date($request->startDate));
            }
            if (isset($request->endDate)) {
                $data = $data->where('order_date', '<=', date($request->endDate));
            }
            $data = $data->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($order) {
                    return view('admin.orders.buttons', ['order' => $order]);
                })
                ->addColumn('user', function ($order) {
                    return $order->user->name;
                })
                ->addColumn('product', function ($order) {

                    return count($order->product);
                })
                ->addColumn('address', function ($order) {
                    return $order->address->name;
                })
                ->addColumn('cost', function ($order) {
                    $totalCost = 0;

                    foreach ($order->product as $product) {
                        $totalCost += $product->price;
                    }

                    return $totalCost . '€';
                })
                ->rawColumns(['action'])
                ->blacklist(['action', 'price'])
                ->make(true);
        }
    }
}
