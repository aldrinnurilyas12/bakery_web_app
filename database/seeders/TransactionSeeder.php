<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
   {
        $faker = Faker::create('id_ID');
        $data = [];

        // id mulai dari 4
        $startId = 5007;

        // tanggal hari ini
        // $today = Carbon::now();
        // $dateCode = $today->format('Ymd');

        // ambil semua customer id
        $customerIds = DB::table('customer')->pluck('customer_code')->toArray();

        for ($i = 0; $i < 2000; $i++) {

            $quantity = $faker->numberBetween(1, 10);
            $price = $faker->numberBetween(10000, 500000);

            $year  = 2026;
            $month = rand(1, 8);

            // ambil jumlah hari valid dalam bulan tsb
            $day = rand(1, Carbon::create($year, $month)->daysInMonth);

            $today = Carbon::create($year, $month, $day);
            $dateCode = $today->format('Ymd');

            $totalAmount = $quantity * $price;
            $grandTotal = $totalAmount;
            $paymentChanges = $faker->numberBetween(0, 50000);
            

            $data[] = [
                'id' => $startId + $i,
                'transaction_code' => 'INV' . $dateCode . Str::random(6),
                'quantity' => $quantity,
                'total_amount' => $totalAmount,
                'payment_changes' => $paymentChanges,
                'grand_total' => $grandTotal,
                'casheer' => '3671121201010001',
                'customer' => $faker->randomElement($customerIds),
                'status' => 5,
                'payment_type' => 1,
                'transaction_date' => $today,
                'created_at' => $today,
                'updated_at' => $today,
            ];
        }

        DB::table('transactions')->insert($data);
    }
}
