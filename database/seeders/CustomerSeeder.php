<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $data = [];
        $startId = 4;
        $startCode =6;
        $dateCode = Carbon::now()->format('ymd');

        for($i = 0; $i < 5000; $i++){
             $memberDate = $faker->dateTimeBetween('-2 years', 'now');

             $data[] = [
                'id' => $startId + $i,
                'customer_code' => 'CUST' . $dateCode . str_pad($startCode + $i, 3, '0', STR_PAD_LEFT),
                'name' => $faker->name,
                'address' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => '62' . $faker->numerify(str_repeat('#', 14)), // 62 + 14 digit = 16
                'member_date' => $memberDate,
                'point' => $faker->numberBetween(0, 5000),
                'status' => 7,
                'created_at' => $memberDate,
                'updated_at' => $memberDate,
            ];
        }

        DB::table('customer')->insert($data);


    }
}
