<?php

declare(strict_types=1);

namespace Velor\Navigation\Repositories;

use Velor\Navigation\Models\Navigation;
use Velor\Navigation\Repositories\Contracts\NavigationRepositoryInterface;

/**
 * @extends AbstractRepository<Navigation>
 */
class NavigationRepository extends AbstractRepository implements NavigationRepositoryInterface
{
    /**
     * @var class-string<Navigation>
     */
    protected string $model = Navigation::class;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Navigation
    {
        return $this->query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Navigation $navigation, array $attributes): Navigation
    {
        $navigation->fill($attributes);
        $navigation->save();

        return $navigation;
    }

    public function delete(Navigation $navigation): void
    {
        $navigation->delete();
    }
}
