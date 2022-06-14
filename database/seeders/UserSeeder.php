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
use function Sodium\add;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->generateClients();
        $this->generateAdmins();
        $this->generateTryUsers();
        $this->generateProductCategories();
        $this->generateOrders();
    }

    private function generateClients()
    {

        User::factory()->count(20)->create([
        ])->each(function ($user) {
            $user->assignRole('client');
            $user->save();
            Address::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }

    private function generateProductCategories()
    {
        $noCategory = ProductCategory::factory()->create([
            'name' => '[Sin categoría]',
        ]);
        $this->generateProducts($noCategory);

        ProductCategory::factory()->count(8)->create()->each(function ($productCategory) {

            $this->generateProducts($productCategory);

        });
    }

    private function generateProducts($productCategory)
    {
        Product::factory()->count(rand(1, 10))->create([
            'product_category_id' => $productCategory,
        ]);
    }

    private function generateOrders()
    {
        Order::factory()->count(1000)->create([
            'user_id' => 1,
            'total' => 0,
        ])->each(function ($order) {
            $address = $this->findRandomAddress();

            $order->user_id = $address->user_id;
            $order->address = $address->address;
            $order->postal_code = $address->postal_code;
            $product = $this->findRandomProduct();
            $total = 0;
            for ($i = 0; $i < rand(1, 6); $i++) {
                if (rand(1, 10) > 5) {
                    for ($i = 0; $i < rand(1, 3); $i++) {
                        $order->product()->attach($product);
                        $total += Product::query()->find($product)->first()->price;
                    }
                }
                $newProduct = $this->findRandomProduct();
                $order->product()->attach($newProduct);
                $total += Product::query()->find($newProduct)->first()->price;

            }
            $order->total = $total;
            $order->save();
        });
    }

    private function findRandomAddress()
    {
        return Address::all()->random(1)->first();
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

        Address::factory()->create([
            'user_id' => $user->id,
        ]);

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
