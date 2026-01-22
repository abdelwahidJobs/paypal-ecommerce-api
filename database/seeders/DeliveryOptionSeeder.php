<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('delivery_options')->insert([
            [
                "id" => '1',
                "deliveryDays" => 7,
                "price_cents" => 0,
            ],
            [
                "id" => '2',
                "deliveryDays" => 3,
                "price_cents" => 499,
            ],
            [
                "id" => '3',
                "deliveryDays" => 1,
                "price_cents" => 999,
            ],

        ]);
    }
}
