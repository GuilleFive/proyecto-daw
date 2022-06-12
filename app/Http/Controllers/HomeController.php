<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
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

        if (Auth::check() && Auth::user()->hasRole(['admin', 'super_admin'])) {
            return view('admin.home');
        }
        $products = Product::query()->with(['product_category'])->where('stock', '>', 0)->orderBy('created_at')->orderBy('updated_at')->get();
        return view('home', ['products' => $products]);


    }

    /**
     * @return array
     */
    public function getSalesByCategoryData(): array
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
        arsort($productsByCategory);
        $echartData = [];

        foreach ($productsByCategory as $index => $value) {
            $percentage = round($value / array_sum($productsByCategory) * 100, 2);

            $echartData[] = ['value' => $percentage, 'name' => $index];
        }
        return $echartData;
    }

    public function getSalesByDayData()
    {
        $orders = Order::query()->orderBy('order_date')->get();

        $ordersByDay = $this->getWeekArray();

        foreach ($orders as $order) {
            $currentDay = date('D', strtotime($order->order_date));

            $ordersByDay[$currentDay]++;
        }
        $averageOrders = [];
        $weeks = $this->getWeeksBetween($orders[0]->order_date, $orders[count($orders) - 1]->order_date);
        foreach ($ordersByDay as $day => $value) {
            $averageOrders[$day] = round($value / $weeks, 2);
        }

        return array_values($averageOrders);

    }

    function getTopSales()
    {
        $startMonth = date(now()->year . '-' . now()->month . '-01');

        $orders = Order::with('product')->whereBetween('order_date', [date($startMonth), now()])->get();

        $products = [];
        foreach ($orders as $order)
            foreach ($order->product as $product) {
                !isset($products[$product->name]) ?
                    $products[$product->name] = 1 :
                    $products[$product->name]++;
            }

        return array_search(max($products), $products);

    }

    /**
     * @param $startDate
     * @param $endDate
     * @return float|int
     * @throws \Exception
     */
    private function getWeeksBetween($startDate, $endDate): float|int
    {
        $startDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);
        $weeksBetween = floor(date_diff($startDate, $endDate)->days / 7);

        if ($weeksBetween != 0)
            return $weeksBetween;

        return 1;
    }

    /**
     * @return array
     */
    private function getWeekArray(): array
    {
        $array = [];

        $array['Mon'] = 0;
        $array['Tue'] = 0;
        $array['Wed'] = 0;
        $array['Thu'] = 0;
        $array['Fri'] = 0;
        $array['Sat'] = 0;
        $array['Sun'] = 0;
        return $array;
    }


}
