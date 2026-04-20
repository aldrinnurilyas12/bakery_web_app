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
        $startId = (DB::table('transactions')->max('id') ?? 0) + 1;
        // ambil semua customer id
        $customerIds = DB::table('customer')->pluck('customer_code')->toArray();
        $emp_code = DB::table('employee')->where('position', 'CSR')->pluck('nik')->toArray();
        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::create(2026, 1, 1);

        for ($i = 0; $i < 250; $i++) {

            // $quantity = $faker->numberBetween(1, 5);
            $amount = $faker->numberBetween(10000, 50000);

            $year  = 2026;
            $month = rand(1, 3);
            $hour = rand(8, 21); // jam 08 - 21
            $minute = rand(0, 59);
            $second = rand(0, 59);

            // ambil jumlah hari valid dalam bulan tsb
            $day = rand(1, Carbon::create($year, $month)->daysInMonth);

            $today = Carbon::create($year, $month, $day);
            $dateCode = $today->format('Ymd');

            $totalAmount = $amount;
            $grandTotal = $amount;
            $paymentChanges = $faker->numberBetween(0, 50000);

            $randomTimestamp = rand($startDate->timestamp, $endDate->timestamp);
            $transactionDate = Carbon::create(
                $year,
                $month,
                $day,
                $hour,
                $minute,
                $second
            );
            $store_code = DB::table('store')->pluck('id')->toArray();
            $casheer = DB::table('employee')->where('position', 'CSR')->pluck('nik')->toArray();
             $paymenttype = DB::table('payment_category')->where('id', '<>', '6')->pluck('id')->toArray();

            $data[] = [
                'transaction_code' => 'INV' . $dateCode . Str::random(6),
                'total_amount' => $totalAmount,
                'payment_changes' => $paymentChanges,
                'grand_total' => $grandTotal,
                'casheer' => $faker->randomElement($casheer),
                'customer' => $faker->randomElement($customerIds),
                'status' => 5,
                'payment_type' => $faker->randomElement($paymenttype),
                'store'=> $faker->randomElement($store_code),
                'transaction_date' => $transactionDate,
                'created_at' =>$transactionDate,
                'created_by' => $faker->randomElement($casheer)
            ];
        }

        DB::table('transactions')->insert($data);
    }
}
