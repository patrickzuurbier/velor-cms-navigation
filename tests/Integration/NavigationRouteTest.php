<?php

declare(strict_types=1);

namespace Velor\Navigation\Tests\Integration;

use App\Enums\LocaleEnum;
use Velor\Navigation\Models\Navigation;
use Velor\Navigation\Models\NavigationItem;
use Tests\Concerns\UsesAuthorization;
use Tests\Integration\AbstractDatabaseIntegrationTestCase;

class NavigationRouteTest extends AbstractDatabaseIntegrationTestCase
{
    use UsesAuthorization;

    public function test_navigation_show_renders_navigation_item_tab(): void
    {
        $this->actingAsAdmin();

        $navigation = Navigation::factory()->create([
            'name' => 'Main navigation',
        ]);

        $response = $this->get(route('navigations.show', ['navigation' => $navigation->id]));

        $response->assertOk();
        $response->assertSee('Main navigation');
        $response->assertSee(route('navigations.navigation-items.index', ['navigation' => $navigation->id]));
    }

    public function test_navigation_items_index_renders_parent_navigation_tab(): void
    {
        $this->actingAsAdmin();

        $navigation = Navigation::factory()->create([
            'name' => 'Footer navigation',
        ]);

        NavigationItem::factory()->for($navigation)->create([
            'text' => [
                'en' => 'Contact',
                'nl' => 'Contact',
            ],
            'url' => [
                'en' => '/contact',
                'nl' => '/contact',
            ],
        ]);

        $response = $this->get(route('navigations.navigation-items.index', ['navigation' => $navigation->id]));

        $response->assertOk();
        $response->assertSee('Contact');
        $response->assertSee(route('navigations.show', ['navigation' => $navigation->id]));
    }

    public function test_navigation_items_index_sorts_translatable_text_for_active_locale(): void
    {
        $user = $this->actingAsAdmin();
        $user->update([
            'settings' => [
                'locale' => LocaleEnum::NL->value,
            ],
        ]);

        $navigation = Navigation::factory()->create([
            'name' => 'Main navigation',
        ]);

        NavigationItem::factory()->for($navigation)->create([
            'text' => [
                'en' => 'Zebra',
                'nl' => 'Aanmelden',
            ],
            'url' => [
                'en' => '/sign-up',
                'nl' => '/aanmelden',
            ],
        ]);

        NavigationItem::factory()->for($navigation)->create([
            'text' => [
                'en' => 'Alpha',
                'nl' => 'Zoeken',
            ],
            'url' => [
                'en' => '/search',
                'nl' => '/zoeken',
            ],
        ]);

        $response = $this->get(route('navigations.navigation-items.index', [
            'navigation' => $navigation->id,
            'sort'       => 'text',
            'direction'  => 'asc',
        ]));

        $response->assertOk();
        $response->assertSeeInOrder(['Aanmelden', 'Zoeken']);
    }

    public function test_navigation_items_can_be_reordered_inside_navigation(): void
    {
        $this->actingAsAdmin();
        $navigation = Navigation::factory()->create();
        $otherNavigation = Navigation::factory()->create();
        $first = NavigationItem::factory()->for($navigation)->create([
            'sort_order' => 1,
        ]);
        $second = NavigationItem::factory()->for($navigation)->create([
            'sort_order' => 2,
        ]);
        $other = NavigationItem::factory()->for($otherNavigation)->create([
            'sort_order' => 1,
        ]);

        $response = $this->postJson(route('resource-row-order.update', ['resource' => 'navigation_items']), [
            'context_key'      => 'navigation_id',
            'context_value'    => (string) $navigation->getKey(),
            'navigation_items' => [
                (string) $other->getKey(),
                (string) $second->getKey(),
                (string) $first->getKey(),
            ],
        ]);

        $response->assertNoContent();
        $this->assertSame(2, $first->refresh()->sort_order);
        $this->assertSame(1, $second->refresh()->sort_order);
        $this->assertSame(1, $other->refresh()->sort_order);
    }

    public function test_navigation_item_cannot_be_viewed_through_another_navigation(): void
    {
        $this->actingAsAdmin();
        $navigation = Navigation::factory()->create();
        $otherNavigation = Navigation::factory()->create();
        $navigationItem = NavigationItem::factory()->for($navigation)->create();

        $response = $this->get(route('navigations.navigation-items.show', [
            'navigation'      => $otherNavigation->getKey(),
            'navigation_item' => $navigationItem->getKey(),
        ]));

        $response->assertNotFound();
    }
}
