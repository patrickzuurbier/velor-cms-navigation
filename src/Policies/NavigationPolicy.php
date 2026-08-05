<?php

declare(strict_types=1);

namespace Velor\Navigation\Policies;

use Velor\Navigation\Models\Navigation;
use App\Models\User;
use App\Policies\Concerns\UsesRolePermissions;

class NavigationPolicy
{
    use UsesRolePermissions;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, Navigation::class, __FUNCTION__);
    }

    public function view(User $user, Navigation $navigation): bool
    {
        return $this->allows($user, Navigation::class, __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->allows($user, Navigation::class, __FUNCTION__);
    }

    public function update(User $user, Navigation $navigation): bool
    {
        return $this->allows($user, Navigation::class, __FUNCTION__);
    }

    public function delete(User $user, Navigation $navigation): bool
    {
        return $this->allows($user, Navigation::class, __FUNCTION__);
    }

    public function restore(User $user, Navigation $navigation): bool
    {
        return $this->allows($user, Navigation::class, __FUNCTION__);
    }

    public function forceDelete(User $user, Navigation $navigation): bool
    {
        return $this->allows($user, Navigation::class, __FUNCTION__);
    }
}
