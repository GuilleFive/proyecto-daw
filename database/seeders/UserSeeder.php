<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->generateAddresses();
        $this->generateProductCategories();
        $this->generateClients();
        $this->generateOrders();
        $this->generateAdmins();
        $this->generateTryUsers();
    }

    private function generateAddresses()
    {
        Address::factory()->count(30)->create();
    }

    private function generateClients()
    {
        User::factory()->count(20)->create([
            'address_id' => 1,
        ])->each(function ($user) {
            $user->assignRole('client');
            $user->address_id = $this->findRandomAddress()->id;
            $user->save();
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

    private function generateOrders()
    {
        Order::factory()->count(50)->create([
            'address_id' => 1,
            'user_id' => 1,
            'product_id' => 1,
        ])->each(function ($order) {
            $user = $this->findRandomUser();
            $order->user_id = $user->id;
            $order->product_id = $this->findRandomProduct();
            $order->address_id = $user->address_id;
            $order->save();
        });
    }

    private function findRandomAddress()
    {
        return Address::all()->random(5)->first();
    }

    private function findRandomUser()
    {
        return User::all()->random(1)->first();
    }

    private function findRandomProduct()
    {
        return Product::all()->random(1)->first()->id;
    }

    private function generateAdmins()
    {
        User::factory()->count(2)->create()->each(
            function ($user) {
                $user->assignRole('admin');
            }
        );
    }

    private function generateTryUsers()
    {
        $user = User::factory()->create([
            'name' => 'client',
            'username' => 'client',
            'password' => Hash::make(1),
        ])->assignRole('client');

        $user->address_id = $this->findRandomAddress()->id;
        $user->save();

        User::factory()->create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => Hash::make(1),
        ])->assignRole('admin');

        User::factory()->create([
            'name' => 'super_admin',
            'username' => 'super_admin',
            'password' => Hash::make(1),
        ])->assignRole('super_admin');

    }
}
