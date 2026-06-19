<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
              InsertSeeder::class,

            // EmployerSeeder::class,
            // JobConfigSeeder::class,
            // AdminUserSeeder::class,
            // JobVacancySeeder::class,
            // AllUsersSeeder::class,
            // LocatorSeeder::class
        ]);
    }
}
