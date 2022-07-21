<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use function Sodium\add;

class OrderController extends Controller
{
    public function getOrders(Request $request)
    {

        if ($request->ajax()) {
            $data = Order::query()->with(['user', 'product', 'address']);

            if (isset($request->startDate)) {
                $data = $data->where('order_date', '>=', date($request->startDate));
            }
            if (isset($request->endDate)) {
                $data = $data->where('order_date', '<=', date($request->endDate));
            }
            if (Auth::user()->hasRole('client'))
                $data = $data->where('user_id', Auth::user()->id);

            $data = $data->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($order) {
                    if (Auth::user()->hasRole('admin'))
                        return view('admin.orders.buttons', ['order' => $order]);
                    else
                        return view('client.orders.buttons', ['order' => $order]);

                })
                ->addColumn('user', function ($order) {
                    return $order->user->name;
                })
                ->addColumn('product', function ($order) {

                    return count($order->product);
                })
                ->addColumn('address', function ($order) {
                    return $order->address->address;
                })
                ->addColumn('postal_code', function ($order) {
                    return $order->address->postal_code;
                })
                ->addColumn('receiver', function ($order) {
                    return $order->address->receiver_name;
                })
                ->addColumn('payment_method', function ($order) {
                    return ucfirst($order->payment_method);
                })
                ->addColumn('cost', function ($order) {

                    return $order->total . '€';
                })
                ->rawColumns(['action'])
                ->blacklist(['action', 'price'])
                ->make(true);
        }
        return null;
    }

    function form(Request $request)
    {
        $addresses = Address::query()->where('user_id', Auth::user()->id)->get();

        $arrayProducts = $request->products;
        $amountProducts = 0;
        foreach ($arrayProducts as $item) {
            $product = Product::query()->where('id', json_decode($item)->id)->first();

            if (json_decode($item)->amount > $product->stock) {

                if ($product->stock !== 1)
                    $message = 'Solo quedan ' . $product->stock . ' unidades';

                else
                    $message = 'Solo queda ' . $product->stock . ' unidad';


                return redirect()->back()->with('product_name', $product->name)->with('message', $message);

            }

            $amountProducts += 1 * json_decode($item)->amount;
        }

        $products = [];
        foreach ($arrayProducts as $productString) {

            $productItem = json_decode($productString);
            $product = Product::query()->where('id', $productItem->id)->first();
            $products[] = ['product' => json_encode($product), 'amount' => $productItem->amount];
        }

        return view('client.orders.form', ['addresses' => $addresses, 'products' => json_encode($products), 'total' => $request->total, 'amountProducts' => $amountProducts]);

    }

    public function createOrder(Request $request)
    {
        if($request->addresses === null)
        return redirect()->back()->with('error', 'Debe crear una dirección para enviar el pedido');

        foreach ($request->products as $productItem) {
            $product = Product::query()->find(json_decode(json_decode($productItem)->product)->id)->first();
            if (json_decode($productItem)->amount > $product->stock) {
                if ($product->stock !== 1)
                    $message = 'Solo quedan ' . $product->stock . ' unidades';
                else
                    $message = 'Solo queda ' . $product->stock . ' unidad';

                return redirect()->route('cart')->with('product_name', $product->name)->with('message', $message);
            }
        }

        $address = Address::query()->find($request->addresses)->id;

        $order = Order::factory()->create([
            'user_id' => Auth::user()->id,
            'address_id' => $address,
            'order_date' => now(),
            'delivery_date' => null,
            'payment_method' => $request->payment_method,
            'total' => $request->total,

        ]);
        foreach ($request->products as $item) {
            $product = Product::query()->where('id', json_decode(json_decode($item)->product)->id)->first();
            for ($i = 0; $i < json_decode($item)->amount; $i++) {
                $order->product()->attach($product->id);

            }
            $product->stock = $product->stock - json_decode($item)->amount;
            $product->save();
        }

        return redirect('orders/show/' . $order->id)->with('done', 'Pedido realizado con éxito');
    }

    public function showOrder(Order $order)
    {
        if (!Auth::user()->hasRole('admin') && Auth::user()->id !== $order->user_id)
            return redirect()->back();

        $products = [];
        foreach ($order->product as $product) {
            $amount = DB::table('order_product')->where('product_id', $product->id)->where('order_id', $order->id)->count();

            $products[] = ['product' => $product, 'amount' => $amount];
        }

        $products = array_unique($products, SORT_REGULAR);

        return view('client.orders.show', ['order' => $order, 'products' => $products]);

    }

    public function cancelOrder(Request $request)
    {

        $order = Order::query()->find($request->order);

        $order->delete();


    }

    public function deliverOrder(Request $request)
    {

        $order = Order::query()->find($request->order);

        $order->delivery_date = now();

        $order->save();
    }
}
