<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Krediti',
            'description' => 'Descrição do plano',
            'price_monthly' => 89.00,
            'price_yearly' => 890.00
        ]);
    }
}
