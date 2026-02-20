<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class TransactionDetailSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // ambil semua transaction_code
        $allTransactions = DB::table('transactions')
            ->pluck('transaction_code')
            ->toArray();

        // ambil transaction_code yang sudah ada detail
        $existingDetails = DB::table('transactions_detail')
            ->pluck('transaction_code')
            ->toArray();

        // cari transaction_code yang belum punya detail
        $missingTransactionCodes = array_diff(
            $allTransactions,
            $existingDetails
        );

        // ambil product & variant
        $products = DB::table('products_daily')
            ->select('daily_code')
            ->get();

        $data = [];
        // $startId = DB::table('transactions_detail')->max('id') + 1;
        $startId = (DB::table('transactions_detail')->max('id') ?? 0) + 1;

         $casheer = DB::table('employee')->where('position', 'CSR')->pluck('nik')->toArray();
       

        foreach ($missingTransactionCodes as $index => $transactionCode) {

            // tanggal random 2026
            $year  = 2026;
            $month = rand(1, 2);
            $hour = rand(8, 21); // jam 08 - 21
            $minute = rand(0, 59);
            $second = rand(0, 59);
            $day   = rand(1, Carbon::create($year, $month)->daysInMonth);
            $today = Carbon::create($year, $month, $day);

            $itemCount = rand(1, 3);

            for ($i = 0; $i <3; $i++) {

                $product = $products->random();
                  $transactionDate = Carbon::create(
                    $year,
                    $month,
                    $day,
                    $hour,
                    $minute,
                    $second
                );

                $data[] = [
                    'transaction_code'      => $transactionCode,
                    'product'               => $product->daily_code,
                    'quantity_per_product'  => $faker->numberBetween(1, 4),
                    'created_at'            => $transactionDate,
                    'created_by'            => $faker->randomElement($casheer),
                    'updated_at'            => $transactionDate,
                ];
            }
        }

        if (!empty($data)) {
            DB::table('transactions_detail')->insert($data);
        }
    }
}
