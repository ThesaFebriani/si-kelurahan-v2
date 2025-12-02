<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            KelurahanSeeder::class,
            RwSeeder::class,
            RtSeeder::class,
            UserSeeder::class,
            JenisSuratSeeder::class,
            TemplateFieldSeeder::class,
        ]);
    }
}
