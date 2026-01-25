<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'uuid' => 'e43638ce-6aa0-4b85-b27f-e1d07eb678c6',
                'name' => 'Black & Gray Athletic Cotton Socks (6 Pairs)',
                'stars' => 4.5,
                'reviews_count' => 87,
                'price_cents' => 1090,
            ],
            [
                'uuid' => '15b6fc6f-327a-4ec4-896f-486349e85a3d',
                'name' => 'Intermediate Composite Basketball',
                'stars' => 4.0,
                'reviews_count' => 127,
                'price_cents' => 2095,
            ],
            [
                'uuid' => '83d4ca15-0f35-48f5-b7a3-1ea210004f2e',
                'name' => 'Adult Plain Cotton T-Shirt (2 Pack) – Teal',
                'stars' => 4.5,
                'reviews_count' => 56,
                'price_cents' => 799,
            ],
            [
                'uuid' => '54e0eccd-8f36-462b-b68a-8182611d9add',
                'name' => '2-Slot Electric Toaster – White',
                'stars' => 5.0,
                'reviews_count' => 2197,
                'price_cents' => 1899,
            ],
            [
                'uuid' => '3ebe75dc-64d2-4137-8860-1f5a963e534b',
                'name' => 'Elegant White Dinner Plate Set (2 Pieces)',
                'stars' => 4.0,
                'reviews_count' => 37,
                'price_cents' => 2067,
            ],
            [
                'uuid' => '8c9c52b5-5a19-4bcb-a5d1-158a74287c53',
                'name' => 'Non-Stick Cooking Pot Set (3 Pieces) – Black',
                'stars' => 4.5,
                'reviews_count' => 175,
                'price_cents' => 3499,
            ],
            [
                'uuid' => 'dd82ca78-a18b-4e2a-9250-31e67412f98d',
                'name' => 'Women’s Cotton Oversized Sweater – Gray',
                'stars' => 4.5,
                'reviews_count' => 317,
                'price_cents' => 2400,
            ],
            [
                'uuid' => '77919bbe-0e56-475b-adde-4f24dfed3a04',
                'name' => 'Luxury Bath Towel Set (2 Pieces) – White',
                'stars' => 4.5,
                'reviews_count' => 144,
                'price_cents' => 3599,
            ],
            [
                'uuid' => '6b07d4e7-f540-454e-8a1e-363f25dbae7d',
                'name' => 'Ultra-Soft Facial Tissue 2-Ply (8 Boxes)',
                'stars' => 4.0,
                'reviews_count' => 99,
                'price_cents' => 1299,
            ],
        ]);
    }
}
