<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $randomDate = mt_rand(strtotime('2010-01-01'), time());
        $delivery = date('Y.m.d', $randomDate);
        $order = date('Y-m-d', strtotime('-'.mt_rand(1, 6).' days', $randomDate));
        return [
            'delivery_date' => $delivery,
            'order_date' => $order,
        ];
    }
}
