<?php

namespace Database\Seeders;

use App\Models\Taskify;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskifySeeder extends Seeder
{
    public function run(): void
    {
        Taskify::factory()->count(20)->create();
    }
}
