<?php

declare(strict_types=1);

namespace Velor\Navigation\Database\Factories;

use App\Concerns\Database\Factories\FakesTranslatables;
use Illuminate\Database\Eloquent\Factories\Factory;
use Velor\Navigation\Models\NavigationItem;

/**
 * @extends Factory<NavigationItem>
 */
class NavigationItemFactory extends Factory
{
    use FakesTranslatables;

    protected $model = NavigationItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'is_active' => $this->faker->boolean(90),
            'text'      => $this->fakeTranslations(fn () => $this->faker->word()),
            'url'       => $this->fakeTranslations(fn () => $this->faker->url()),
        ];
    }
}
