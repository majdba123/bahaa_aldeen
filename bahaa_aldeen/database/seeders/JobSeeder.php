<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jobs')->insert([
            ['title' => 'BranchManager', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Accountant', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'HR', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'SalesManager', 'created_at' => now(), 'updated_at' => now()],
        ]);

    }
}
