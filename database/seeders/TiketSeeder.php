<?php

namespace Database\Seeders;

use App\Models\Tiket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TiketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tiket = [
            [
                'event_id' => 1,
                'tipe' => 'premium',
                'harga' => 1500000,
                'stok' => 100
            ],
            [
                'event_id' => 1,
                'tipe' => 'reguler',
                'harga' => 500000,
                'stok' => 500
            ],
            [
                'event_id' => 2,
                'tipe' => 'premium',
                'harga' => 200000,
                'stok' => 300
            ],
            [
                'event_id' => 3,
                'tipe' => 'premium',
                'harga' => 300000,
                'stok' => 200
            ],
        ];

        foreach ($tiket as $tiket) {
            Tiket::create($tiket);
        }
    }
}
