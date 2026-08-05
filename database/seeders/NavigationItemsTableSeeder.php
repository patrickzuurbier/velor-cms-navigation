<?php

declare(strict_types=1);

namespace Velor\Navigation\Database\Seeders;

use Velor\Navigation\Models\Navigation;
use Velor\Navigation\Models\NavigationItem;
use Illuminate\Database\Seeder;

class NavigationItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        $navigations = Navigation::all();

        foreach ($navigations as $navigation) {
            NavigationItem::factory()->for($navigation)
                ->count(rand(1, 5))
                ->create();
        }
    }
}
