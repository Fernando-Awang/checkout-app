<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listproduct = [];

        $dummyProduct = [
            'sepatu', 'tas', 'sendal', 'baju', 'celana'
        ];
        foreach ($dummyProduct as $name) {
            $product = [];
            $product['name'] = $name;
            $product['price'] = rand(1, 9) . '000';
            $product['description'] = '-';
            $product['sku'] = rand(300,400);
            $product['created_at'] = now();
            $product['updated_at'] = now();
            $listproduct[] = $product;
        }

        DB::table('products')->insert($listproduct);
    }
}
