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

    // ambil semua transaction_code dan created_at dari tabel transactions
    $transactions = DB::table('transactions')
        ->select('transaction_code', 'created_at')
        ->pluck('created_at', 'transaction_code')
        ->toArray();
    // hasil: ['TRX001' => '2026-03-05 10:15:00', ...]

    // ambil transaction_code yang sudah ada di transactions_detail
    $existingDetails = DB::table('transactions_detail')
        ->pluck('transaction_code')
        ->toArray();

    // cari transaction_code yang belum punya detail
    $missingTransactionCodes = array_diff(
        array_keys($transactions), // ambil semua transaction_code
        $existingDetails
    );

    if (empty($missingTransactionCodes)) {
        return; // tidak ada data yang perlu dibuat
    }

    // ambil product & variant
    $products = DB::table('production_products as pp')
        ->join('products_daily as pd', 'pp.production_code', '=', 'pd.production')
        ->select('pp.product', 'pp.variant')
        ->get();

    // ambil daftar kasir
    $casheer = DB::table('employee')
        ->where('position', 'CSR')
        ->pluck('nik')
        ->toArray();

    $data = [];

    foreach ($missingTransactionCodes as $transactionCode) {

        $transactionDate = $transactions[$transactionCode]; // ambil created_at dari transaksi

        // jumlah item per transaksi, bisa random 1-3
        $itemCount = rand(1, 3);

        for ($i = 0; $i < $itemCount; $i++) {
            $product = $products->random();

            $data[] = [
                'transaction_code'     => $transactionCode,
                'product'              => $product->product,
                'variant'              => $product->variant,
                'quantity_per_product' => $faker->numberBetween(1, 3),
                'created_at'           => $transactionDate,
                'updated_at'           => $transactionDate,
                'created_by'           => $faker->randomElement($casheer),
            ];
        }
    }

    if (!empty($data)) {
        DB::table('transactions_detail')->insert($data);
    }
}
}
