<?php

declare(strict_types=1);

namespace Velor\Navigation\Repositories\Contracts;

use Velor\Navigation\Models\Navigation;
use Velor\Navigation\Models\NavigationItem;

interface NavigationItemRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createForNavigation(Navigation $navigation, array $attributes): NavigationItem;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(NavigationItem $navigationItem, array $attributes): NavigationItem;

    public function delete(NavigationItem $navigationItem): void;
}
