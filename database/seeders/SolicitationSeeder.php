<?php

namespace Database\Seeders;

use App\Models\Solicitation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SolicitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Solicitation::factory(10)->create();
        
    }
}
