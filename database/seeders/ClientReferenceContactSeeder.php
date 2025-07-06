<?php

namespace Database\Seeders;

use App\Models\ClientReferenceContact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientReferenceContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClientReferenceContact::factory(10)->create();
        
    }
}
