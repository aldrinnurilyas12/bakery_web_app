<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $data = [];

        // Rentang tanggal untuk date_code
        $start = Carbon::create(2025, 1, 1);
        $end = Carbon::create(2026, 1, 1);

        for ($i = 0; $i < 200; $i++) {

            // Generate date_code acak per record
            $randomDate = Carbon::createFromTimestamp(rand($start->timestamp, $end->timestamp));
            $date_code = $randomDate->format('ymd');

            // Generate kode unik (ambil 6 karakter dari UUID)
            $unique_code = substr((string) Str::uuid(), 0, 6);
            $customer_code = 'cust' . $date_code . $unique_code;

            // Generate member date
            $memberDate = $faker->dateTimeBetween('-2 years', 'now');

            $data[] = [
                // 'id' tidak perlu di-set kalau auto-increment
                'customer_code' => $customer_code,
                'name' => $faker->name,
                'address' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'birth_date' => $faker->date('Y-m-d', '2005-12-31'),
                'phone_number' => '62' . $faker->numerify(str_repeat('#', 14)), // 62 + 14 digit = 16
                'member_date' => $memberDate,
                'point' => $faker->numberBetween(0, 10),
                'status' => 7,
                'created_at' => $memberDate,
                'updated_at' => $memberDate,
            ];
        }

        DB::table('customer')->insert($data);
    }
}
