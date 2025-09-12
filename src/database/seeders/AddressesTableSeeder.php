<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addresses')->insert([
            [
                'user_id' => 1,
                'postal_code' => '838-0816',
                'address' => '福岡県朝倉郡筑前町新町',
            ],
            [
                'user_id' => 2,
                'postal_code' => '813-0008',
                'address' => '福岡県粕屋郡粕屋町内橋',
            ],
            [
                'user_id' => 3,
                'postal_code' => '838-0816',
                'address' => '福岡県遠賀郡芦屋町白浜町',
            ],
        ]);
    }
}
