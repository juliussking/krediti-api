<?php

namespace Database\Seeders;

use App\Models\Liberation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LiberationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Liberation::factory(10)->create();
    }
}
