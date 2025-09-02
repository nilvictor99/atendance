<?php

namespace Database\Seeders;

use Database\Factories\TimesheetFactory;
use Illuminate\Database\Seeder;

class FakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TimesheetFactory::new()->count(10)->create();
    }
}
