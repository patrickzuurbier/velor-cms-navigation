<?php

declare(strict_types=1);

namespace Velor\Navigation\Tests\Integration;

use Velor\Navigation\Models\Navigation;
use Velor\Navigation\Models\NavigationItem;
use Velor\Navigation\Repositories\Contracts\NavigationItemRepositoryInterface;
use Tests\Integration\AbstractDatabaseIntegrationTestCase;

class NavigationItemRepositoryTest extends AbstractDatabaseIntegrationTestCase
{
    protected NavigationItemRepositoryInterface $navigationItemRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->navigationItemRepository = $this->getApplication()->make(NavigationItemRepositoryInterface::class);
    }

    public function test_it_creates_a_navigation_item_for_a_navigation(): void
    {
        $navigation = Navigation::factory()->create();

        $navigationItem = $this->navigationItemRepository->createForNavigation($navigation, [
            'text' => [
                'en' => 'Contact',
                'nl' => 'Contact',
            ],
            'url' => [
                'en' => '/contact',
                'nl' => '/contact',
            ],
            'is_active'  => true,
            'sort_order' => null,
        ]);

        $this->assertDatabaseHas('navigation_items', [
            'id'            => $navigationItem->getKey(),
            'navigation_id' => $navigation->getKey(),
            'is_active'     => true,
        ]);
    }

    public function test_it_updates_a_navigation_item(): void
    {
        $navigationItem = NavigationItem::factory()->create([
            'text' => [
                'en' => 'Contact',
                'nl' => 'Contact',
            ],
        ]);

        $updatedNavigationItem = $this->navigationItemRepository->update($navigationItem, [
            'text' => [
                'en' => 'About',
                'nl' => 'Over ons',
            ],
            'url' => [
                'en' => '/about',
                'nl' => '/over-ons',
            ],
            'is_active'  => false,
            'sort_order' => 1,
        ]);

        $this->assertSame('About', $updatedNavigationItem->getTranslation('text', 'en'));
        $this->assertSame('Over ons', $updatedNavigationItem->getTranslation('text', 'nl'));
        $this->assertFalse($updatedNavigationItem->is_active);
    }

    public function test_it_deletes_a_navigation_item(): void
    {
        $navigationItem = NavigationItem::factory()->create();

        $this->navigationItemRepository->delete($navigationItem);

        $this->assertDatabaseMissing('navigation_items', [
            'id' => $navigationItem->getKey(),
        ]);
    }
}
