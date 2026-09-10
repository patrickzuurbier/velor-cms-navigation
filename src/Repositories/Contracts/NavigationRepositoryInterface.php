<?php

declare(strict_types=1);

namespace Velor\Navigation\Repositories\Contracts;

use Velor\Navigation\Models\Navigation;

interface NavigationRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Navigation;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Navigation $navigation, array $attributes): Navigation;

    public function delete(Navigation $navigation): void;
}
