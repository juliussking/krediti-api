<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientAddress;
use App\Models\ClientDocument;
use App\Models\ClientOffice;
use App\Models\clientProfile;
use App\Models\ClientReferenceContact;
use App\Models\Company;
use App\Models\Liberation;
use App\Models\Payment;
use App\Models\Solicitation;
use App\Models\Task;
use App\Models\User;
use App\Models\UserProfile;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([

            UserSeeder::class,
            CompanySeeder::class,
            ClientSeeder::class,
            SolicitationSeeder::class,
            TaskSeeder::class,
            LiberationSeeder::class,
            PaymentSeeder::class,
            ClientProfileSeeder::class,
            ClientAddressSeeder::class,
            ClientOfficeSeeder::class,
            ClientReferenceContactSeeder::class,
            UserProfileSeeder::class,

        ]);
    }
}
