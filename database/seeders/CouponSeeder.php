<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $cupones = [
            [
                'name' => 'descuento10',
                'discount' => 10,
                'valid_until' => Carbon::now()->addDays(15),
            ],
            [
                'name' => 'ahorra20',
                'discount' => 20,
                'valid_until' => Carbon::now()->addDays(30),
            ],
            [
                'name' => 'oferta30',
                'discount' => 30,
                'valid_until' => Carbon::now()->addDays(10),
            ],
            [
                'name' => 'promo40',
                'discount' => 40,
                'valid_until' => Carbon::now()->addDays(5),
            ],
        ];

        foreach ($cupones as $cupon) {
            Coupon::create($cupon);
        }
    }
}
