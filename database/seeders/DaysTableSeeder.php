<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $days = [
            [
                'name' => "Senin",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
            [
                'name' => "Selasa",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
            [
                'name' => "Rabu",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
            [
                'name' => "Kamis",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
            [
                'name' => "Jumat",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
            [
                'name' => "Sabtu",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
            [
                'name' => "Minggu",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
        ];

        DB::table('days')->insert($days);
    }
}
