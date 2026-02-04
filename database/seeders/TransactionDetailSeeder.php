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
            ->select('product_code', 'variant_code')
            ->get();

        $data = [];
        $startId = DB::table('transactions_detail')->max('id') + 1;

        foreach ($missingTransactionCodes as $index => $transactionCode) {

            // tanggal random 2026
            $year  = 2026;
            $month = rand(1, 12);
            $day   = rand(1, Carbon::create($year, $month)->daysInMonth);
            $today = Carbon::create($year, $month, $day);

            // 1–3 item per transaksi (lebih realistis)
            $itemCount = rand(1, 3);

            for ($i = 0; $i < $itemCount; $i++) {

                $product = $products->random();

                $data[] = [
                    'id' => $startId++,
                    'transaction_code'      => $transactionCode,
                    'product'               => $product->product_code,
                    'variant'               => $product->variant_code,
                    'quantity_per_product'  => $faker->numberBetween(1, 10),
                    'created_at'            => $today,
                    'created_by'            => '3671121201010001',
                    'updated_at'            => $today,
                ];
            }
        }

        if (!empty($data)) {
            DB::table('transactions_detail')->insert($data);
        }
    }
}
