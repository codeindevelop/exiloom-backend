<?php

namespace Database\Seeders;

use Database\Seeders\Auth\PermissionsSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // Permissions And User Seeders
        $this->call(PermissionsSeeder::class);
    }
}
