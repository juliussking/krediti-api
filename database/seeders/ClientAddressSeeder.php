<?php

namespace Database\Seeders;

use App\Models\ClientAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClientAddress::factory(10)->create();
        
    }
}
