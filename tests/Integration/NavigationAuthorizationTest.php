<?php

declare(strict_types=1);

namespace Velor\Navigation\Tests\Integration;

use App\Models\Role;
use App\Models\User;
use Tests\Integration\AbstractDatabaseIntegrationTestCase;
use Velor\Navigation\Models\Navigation;
use Velor\Navigation\Models\NavigationItem;

class NavigationAuthorizationTest extends AbstractDatabaseIntegrationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::factory()->create([
            'name'       => 'Unauthorized',
            'slug'       => 'unauthorized',
            'privileges' => [],
        ]);

        $this->actingAs(User::factory()->create([
            'role_id' => $role->getKey(),
        ]));
    }

    public function test_navigation_read_routes_require_their_policy_privileges(): void
    {
        $navigation = Navigation::factory()->create();

        $this->getJson(route('navigations.index'))->assertForbidden();
        $this->getJson(route('navigations.create'))->assertForbidden();
        $this->getJson(route('navigations.show', ['navigation' => $navigation]))->assertForbidden();
    }

    public function test_navigation_write_routes_require_their_policy_privileges(): void
    {
        $navigation = Navigation::factory()->create(['name' => 'Original navigation']);

        $this->postJson(route('navigations.store'))->assertForbidden();
        $this->putJson(route('navigations.update', ['navigation' => $navigation]))->assertForbidden();
        $this->deleteJson(route('navigations.destroy', ['navigation' => $navigation]))->assertForbidden();

        $this->assertSame('Original navigation', $navigation->refresh()->getAttribute('name'));
        $this->assertSame(1, Navigation::query()->count());
    }

    public function test_navigation_item_read_routes_require_their_policy_privileges(): void
    {
        $navigation = Navigation::factory()->create();
        $navigationItem = NavigationItem::factory()->for($navigation)->create();

        $this->getJson(route('navigations.navigation-items.index', ['navigation' => $navigation]))
            ->assertForbidden();
        $this->getJson(route('navigations.navigation-items.create', ['navigation' => $navigation]))
            ->assertForbidden();
        $this->getJson(route('navigations.navigation-items.show', [
            'navigation'      => $navigation,
            'navigation_item' => $navigationItem,
        ]))->assertForbidden();
    }

    public function test_navigation_item_write_routes_require_their_policy_privileges(): void
    {
        $navigation = Navigation::factory()->create();
        $navigationItem = NavigationItem::factory()->for($navigation)->create([
            'text' => [
                'en' => 'Original item',
                'nl' => 'Origineel item',
            ],
        ]);

        $this->postJson(route('navigations.navigation-items.store', ['navigation' => $navigation]))
            ->assertForbidden();
        $this->putJson(route('navigations.navigation-items.update', [
            'navigation'      => $navigation,
            'navigation_item' => $navigationItem,
        ]))->assertForbidden();
        $this->deleteJson(route('navigations.navigation-items.destroy', [
            'navigation'      => $navigation,
            'navigation_item' => $navigationItem,
        ]))->assertForbidden();

        $this->assertSame('Original item', $navigationItem->refresh()->getTranslation('text', 'en'));
        $this->assertSame(1, NavigationItem::query()->count());
    }
}
