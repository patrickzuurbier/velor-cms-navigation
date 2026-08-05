<?php

declare(strict_types=1);

namespace Velor\Navigation\Database\Seeders;

use Velor\Navigation\Models\Navigation;
use Illuminate\Database\Seeder;

class NavigationsTableSeeder extends Seeder
{
    public function run(): void
    {
        Navigation::factory()->count(5)->create();
    }
}
