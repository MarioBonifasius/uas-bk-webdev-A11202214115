<?php

namespace Database\Seeders;

use App\Models\DetailOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $detail = [
            [
                'order_id' => 1,
                'tiket_id' => 1,
                'jumlah' => 1,
                'subtotal_harga' => 1500000,
            ],
            [
                'order_id' => 2,
                'tiket_id' => 3,
                'jumlah' => 1,
                'subtotal_harga' => 200000,
            ],
        ];
        foreach ($detail as $detail) {
            DetailOrder::create($detail);
        }
    }
}
