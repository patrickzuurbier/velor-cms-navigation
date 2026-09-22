<?php

declare(strict_types=1);

namespace Velor\Navigation\Tests\Integration;

use Velor\Navigation\Models\Navigation;
use Velor\Navigation\Repositories\Contracts\NavigationRepositoryInterface;
use Tests\Integration\AbstractDatabaseIntegrationTestCase;

class NavigationRepositoryTest extends AbstractDatabaseIntegrationTestCase
{
    protected NavigationRepositoryInterface $navigationRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->navigationRepository = $this->app->make(NavigationRepositoryInterface::class);
    }

    public function test_it_creates_a_navigation(): void
    {
        $navigation = $this->navigationRepository->create([
            'name' => 'Footer navigation',
        ]);

        $this->assertDatabaseHas('navigations', [
            'id'   => $navigation->getKey(),
            'name' => 'Footer navigation',
        ]);
    }

    public function test_it_updates_a_navigation(): void
    {
        $navigation = Navigation::factory()->create([
            'name' => 'Main navigation',
        ]);

        $updatedNavigation = $this->navigationRepository->update($navigation, [
            'name' => 'Primary navigation',
        ]);

        $this->assertSame('Primary navigation', $updatedNavigation->getAttribute('name'));
    }

    public function test_it_deletes_a_navigation(): void
    {
        $navigation = Navigation::factory()->create();

        $this->navigationRepository->delete($navigation);

        $this->assertDatabaseMissing('navigations', [
            'id' => $navigation->getKey(),
        ]);
    }
}
