<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder  extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::first();
        DB::table('carts')->insert([
            [
                "id" => '1',
                "user_id" => $user->id,
            ],
        ]);
    }
}
