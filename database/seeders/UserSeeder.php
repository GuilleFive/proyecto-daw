<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->generateProductCategories();
        $this->generateUsers();
        $this->generateOrders();
    }

    private function generateUsers()
    {
        User::factory()->count(10)->create()->each(function ($user) {

            $this->generateAddress($user);

        });
    }

    private function generateProductCategories()
    {
        ProductCategory::factory()->count(10)->create()->each(function ($productCategory) {

            $this->generateProducts($productCategory);

        });
    }

    private function generateProducts($productCategory)
    {
        Product::factory()->count(rand(3, 20))->create([
            'product_category_id' => $productCategory,
        ]);
    }

    private function generateAddress($user)
    {
        Address::factory()->count(1)->create([
            'user_id' => $user
        ]);
    }

    private function generateOrders(){
        Order::factory()->count(50)->create([
            'address_id' => 1,
            'user_id' => 1,
            'product_id' => 1,
        ])->each(function($order){
            $user = $this->findRandomUser();

            $order->user_id = $user;
            $order->product_id = $this->findRandomProduct();
            $order->address_id = $this->findAddress($user);
            $order->save();
        });
    }

    private function findRandomUser(){
       return User::all()->random(1)->first()->id;
    }
    private function findRandomProduct(){
        return Product::all()->random(1)->first()->id;
    }
    private function findAddress($user){
        return Address::query()->where('user_id', '=', $user)->first()->id;
    }

}
