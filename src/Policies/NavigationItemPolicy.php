<?php

declare(strict_types=1);

namespace Velor\Navigation\Policies;

use Velor\Navigation\Models\NavigationItem;
use App\Models\User;
use App\Policies\Concerns\UsesRolePermissions;

class NavigationItemPolicy
{
    use UsesRolePermissions;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, NavigationItem::class, __FUNCTION__);
    }

    public function view(User $user, NavigationItem $navigationItem): bool
    {
        return $this->allows($user, NavigationItem::class, __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->allows($user, NavigationItem::class, __FUNCTION__);
    }

    public function update(User $user, NavigationItem $navigationItem): bool
    {
        return $this->allows($user, NavigationItem::class, __FUNCTION__);
    }

    public function reorder(User $user): bool
    {
        return $this->allows($user, NavigationItem::class, __FUNCTION__);
    }

    public function delete(User $user, NavigationItem $navigationItem): bool
    {
        return $this->allows($user, NavigationItem::class, __FUNCTION__);
    }

    public function restore(User $user, NavigationItem $navigationItem): bool
    {
        return $this->allows($user, NavigationItem::class, __FUNCTION__);
    }

    public function forceDelete(User $user, NavigationItem $navigationItem): bool
    {
        return $this->allows($user, NavigationItem::class, __FUNCTION__);
    }
}
