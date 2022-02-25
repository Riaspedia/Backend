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
                'name' => "Monday",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
            [
                'name' => "Tuesday",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
            [
                'name' => "Wednesday",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
            [
                'name' => "Thursday",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
            [
                'name' => "Friday",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
            [
                'name' => "Saturday",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
            [
                'name' => "Sunday",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
        ];

        DB::table('days')->insert($days);
    }
}
