<?php

namespace Database\Seeders;

use App\Models\clientProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        clientProfile::factory(10)->create();
        
    }
}
