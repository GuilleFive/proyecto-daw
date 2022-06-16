<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
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

        if (Auth::check() && Auth::user()->hasRole(['admin', 'super_admin'])) {
            return view('admin.home');
        }

        return view('home');


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

    public function getProducts(Request $request)
    {
        $products = Product::query()->with(['product_category'])->where('stock', '>', 0);
        if (isset($request->name) && $request->name !== 'undefined') {
            $products = $products->where('name', 'LIKE', "%$request->name%");
            $order = 'new';
            $category = '';
            $totalProducts = count($products->get());
        } else {

            $category = '';
            if (isset($request->name) && $request->name !== 'undefined') {
                $products = $products->where('name', $request->name);
            }
            if (isset($request->category) && $request->category !== 'undefined') {
                $products = $products->where('product_category_id', $request->category);
                $category = $request->category;
            }

            if (isset($request->order) && $request->order !== 'undefined') {
                if ($request->order === 'old') {
                    $products = $products->orderBy('updated_at', 'ASC')->orderBy('created_at', 'ASC');
                    $order = 'old';
                } elseif ($request->order === 'new') {
                    $products = $products->orderBy('updated_at', 'DESC')->orderBy('created_at', 'DESC');
                    $order = 'new';
                } elseif ($request->order === 'cheap') {
                    $products = $products->orderBy('price', 'ASC')->orderBy('updated_at', 'DESC')->orderBy('created_at', 'ASC');
                    $order = 'cheap';
                } else {
                    $products = $products->orderBy('price', 'DESC')->orderBy('updated_at', 'DESC')->orderBy('created_at', 'ASC');
                    $order = 'expen';
                }
            } else {
                $products = $products->orderBy('updated_at', 'DESC')->orderBy('created_at', 'DESC');
                $order = 'new';
            }
            $totalProducts = count($products->get());
            if (isset($request->length) && $request->category !== 'undefined')
                $products = $products->limit($request->length);
            else
                $products = $products->limit(18);
        }

        $products = $products->get();

        $media = [];
        foreach ($products as $product) {
            $media[$product->id] = $product->getMedia()[0]->getFullUrl();
        }

        return json_encode(['products' => json_encode($products), 'media' => json_encode($media), 'length' => count($products), 'order' => $order, 'category' => $category, 'total' => $totalProducts]);
    }

    public function getCategories()
    {

        $categories = ProductCategory::query()->orderBy('name')->get();

        $response = '<p class="h5 ms-3 mb-4">Categorías</p><div class="d-flex flex-column ms-5"><a class="w-100 mb-2 text-primary-dark text-decoration-none filter-category pointer selected" data-category="">Todo</a>';
        foreach ($categories as $category) {
            $response .= "<a class='w-100 mb-2 text-primary-dark text-decoration-none filter-category pointer' data-category='$category->id'>$category->name</a>";
        }

        $response .= '</div>';

        return $response;
    }


}
