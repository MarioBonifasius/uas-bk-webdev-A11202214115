<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order = [
            [
                'user_id' => 2,
                'event_id' => 1,
                'order_date' => '2024-07-01 14:30:00',
                'total_harga' => 1500000
            ],
            [
                'user_id' => 2,
                'event_id' => 2,
                'order_date' => '2024-07-02 10:15:00',
                'total_harga' => 200000,
            ]
        ];
        foreach ($order as $order) {
            Order::create($order);
        }
    }
}
