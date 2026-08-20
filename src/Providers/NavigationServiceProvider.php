<?php

declare(strict_types=1);

namespace Velor\Navigation\Providers;

use App\Services\Authorization\Contracts\PolicyRegistryInterface;
use App\Services\CmsMenu\Contracts\CmsMenuItemRegistryInterface;
use App\Services\CmsMenu\Data\CmsMenuItemData;
use App\Services\CmsRouting\Contracts\CmsRouteRegistrarInterface;
use App\Services\Resources\Contracts\ResourceRegistryInterface;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\ServiceProvider;
use Velor\Navigation\Models\Navigation;
use Velor\Navigation\Models\NavigationItem;
use Velor\Navigation\Policies\NavigationItemPolicy;
use Velor\Navigation\Policies\NavigationPolicy;
use Velor\Navigation\Resources\NavigationItemResource;
use Velor\Navigation\Resources\NavigationResource;

class NavigationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/velor-navigation.php', 'velor-navigation');
    }

    public function boot(
        CmsRouteRegistrarInterface $cmsRoutes,
        ResourceRegistryInterface $resources,
        PolicyRegistryInterface $policies,
        CmsMenuItemRegistryInterface $cmsMenuItems,
        ConfigRepository $config,
    ): void {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'velor-navigation');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $resources->register($this->configuredClass($config, 'velor-navigation.resources.navigation', NavigationResource::class));
        $resources->register($this->configuredClass($config, 'velor-navigation.resources.navigation_item', NavigationItemResource::class));

        $policies->register(Navigation::class, $this->configuredClass($config, 'velor-navigation.policies.' . Navigation::class, NavigationPolicy::class));
        $policies->register(NavigationItem::class, $this->configuredClass($config, 'velor-navigation.policies.' . NavigationItem::class, NavigationItemPolicy::class));

        $cmsMenuItems->registerBefore(
            'users.index',
            new CmsMenuItemData(Navigation::class, 'navigations.index', 'velor-navigation::resources.navigations.plural', 'bi-list'),
        );

        $cmsRoutes->loadAuthenticated(__DIR__ . '/../../routes/cms.php');

        $this->publishes([
            __DIR__ . '/../../config/velor-navigation.php' => $this->app->configPath('velor-navigation.php'),
        ], 'velor-navigation-config');

        $this->publishes([
            __DIR__ . '/../../database/migrations' => $this->app->databasePath('migrations'),
        ], 'velor-navigation-migrations');

        $this->publishes([
            __DIR__ . '/../../database/seeders' => $this->app->databasePath('seeders'),
        ], 'velor-navigation-seeders');

        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/velor-navigation'),
        ], 'velor-navigation-lang');
    }

    /**
     * @param class-string $default
     *
     * @return class-string
     */
    protected function configuredClass(ConfigRepository $config, string $key, string $default): string
    {
        $value = $config->get($key);

        if (! is_string($value) || ! class_exists($value)) {
            return $default;
        }

        return $value;
    }
}
