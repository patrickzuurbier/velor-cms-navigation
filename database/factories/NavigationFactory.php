<?php

declare(strict_types=1);

namespace Velor\Navigation\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Velor\Navigation\Models\Navigation;

/**
 * @extends Factory<Navigation>
 */
class NavigationFactory extends Factory
{
    protected $model = Navigation::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
