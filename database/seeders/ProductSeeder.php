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
                'id' => 'e43638ce-6aa0-4b85-b27f-e1d07eb678c6',
                'name' => 'Black & Gray Athletic Cotton Socks (6 Pairs)',
//                'image' => 'images/products/athletic-cotton-socks-6-pairs.jpg',
                'stars' => 4.5,
                'reviews_count' => 87,
                'price_cents' => 1090,
            ],
            [
                'id' => '15b6fc6f-327a-4ec4-896f-486349e85a3d',
                'name' => 'Intermediate Composite Basketball',
//                'image' => 'images/products/intermediate-composite-basketball.jpg',
                'stars' => 4.0,
                'reviews_count' => 127,
                'price_cents' => 2095,
            ],
            [
                'id' => '83d4ca15-0f35-48f5-b7a3-1ea210004f2e',
                'name' => 'Adult Plain Cotton T-Shirt (2 Pack) – Teal',
//                'image' => 'images/products/adults-plain-cotton-tshirt-2-pack-teal.jpg',
                'stars' => 4.5,
                'reviews_count' => 56,
                'price_cents' => 799,
            ],
            [
                'id' => '54e0eccd-8f36-462b-b68a-8182611d9add',
                'name' => '2-Slot Electric Toaster – White',
//                'image' => 'images/products/2-slot-toaster-white.jpg',
                'stars' => 5.0,
                'reviews_count' => 2197,
                'price_cents' => 1899,
            ],
            [
                'id' => '3ebe75dc-64d2-4137-8860-1f5a963e534b',
                'name' => 'Elegant White Dinner Plate Set (2 Pieces)',
//                'image' => 'images/products/elegant-white-dinner-plate-set.jpg',
                'stars' => 4.0,
                'reviews_count' => 37,
                'price_cents' => 2067,
            ],
            [
                'id' => '8c9c52b5-5a19-4bcb-a5d1-158a74287c53',
                'name' => 'Non-Stick Cooking Pot Set (3 Pieces) – Black',
//                'image' => 'images/products/3-piece-cooking-set.jpg',
                'stars' => 4.5,
                'reviews_count' => 175,
                'price_cents' => 3499,
            ],
            [
                'id' => 'dd82ca78-a18b-4e2a-9250-31e67412f98d',
                'name' => 'Women’s Cotton Oversized Sweater – Gray',
//                'image' => 'images/products/women-plain-cotton-oversized-sweater-gray.jpg',
                'stars' => 4.5,
                'reviews_count' => 317,
                'price_cents' => 2400,
            ],
            [
                'id' => '77919bbe-0e56-475b-adde-4f24dfed3a04',
                'name' => 'Luxury Bath Towel Set (2 Pieces) – White',
//                'image' => 'images/products/luxury-towel-set.jpg',
                'stars' => 4.5,
                'reviews_count' => 144,
                'price_cents' => 3599,
            ],
            [
                'id' => '6b07d4e7-f540-454e-8a1e-363f25dbae7d',
                'name' => 'Ultra-Soft Facial Tissue 2-Ply (8 Boxes)',
//                'image' => 'images/products/facial-tissue-2-ply-8-boxes.jpg',
                'stars' => 4.0,
                'reviews_count' => 99,
                'price_cents' => 1299,
            ],
        ]);
    }
}
