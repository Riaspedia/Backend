<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => "User",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
            [
                'name' => "Owner",
                'created_at' => new \DateTime,
                'updated_at' => null,
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
