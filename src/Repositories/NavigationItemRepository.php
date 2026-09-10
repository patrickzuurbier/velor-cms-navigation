<?php

declare(strict_types=1);

namespace Velor\Navigation\Repositories;

use Velor\Navigation\Models\Navigation;
use Velor\Navigation\Models\NavigationItem;
use Velor\Navigation\Repositories\Contracts\NavigationItemRepositoryInterface;

/**
 * @extends AbstractRepository<NavigationItem>
 */
class NavigationItemRepository extends AbstractRepository implements NavigationItemRepositoryInterface
{
    /**
     * @var class-string<NavigationItem>
     */
    protected string $model = NavigationItem::class;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createForNavigation(Navigation $navigation, array $attributes): NavigationItem
    {
        return $navigation->navigationItems()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(NavigationItem $navigationItem, array $attributes): NavigationItem
    {
        $navigationItem->fill($attributes);
        $navigationItem->save();

        return $navigationItem;
    }

    public function delete(NavigationItem $navigationItem): void
    {
        $navigationItem->delete();
    }
}
