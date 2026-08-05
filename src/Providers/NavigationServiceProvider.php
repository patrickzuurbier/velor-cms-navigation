<?php

declare(strict_types=1);

namespace Velor\Navigation\Providers;

use App\Data\Cms\SidebarItemData;
use Illuminate\Support\ServiceProvider;
use Velor\Navigation\Models\Navigation;
use Velor\Navigation\Models\NavigationItem;
use Velor\Navigation\Policies\NavigationItemPolicy;
use Velor\Navigation\Policies\NavigationPolicy;
use Velor\Navigation\Resources\NavigationItemResource;
use Velor\Navigation\Resources\NavigationResource;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use App\Services\Resources\Contracts\ResourceRegistryInterface;
use App\Services\CmsRouting\Contracts\CmsRouteRegistrarInterface;
use App\Services\Authorization\Contracts\PolicyRegistryInterface;
use App\Services\CmsNavigation\Contracts\SidebarItemRegistryInterface;

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
        SidebarItemRegistryInterface $sidebarItems,
        ConfigRepository $config,
    ): void {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'velor-navigation');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        if ($config->get('velor-navigation.enabled') === true) {
            $resources->register($this->configuredClass($config, 'velor-navigation.resources.navigation', NavigationResource::class));
            $resources->register($this->configuredClass($config, 'velor-navigation.resources.navigation_item', NavigationItemResource::class));

            $policies->register(Navigation::class, $this->configuredClass($config, 'velor-navigation.policies.' . Navigation::class, NavigationPolicy::class));
            $policies->register(NavigationItem::class, $this->configuredClass($config, 'velor-navigation.policies.' . NavigationItem::class, NavigationItemPolicy::class));

            $sidebarItems->registerBefore(
                'users.index',
                new SidebarItemData(Navigation::class, 'navigations.index', 'velor-navigation::resources.navigations.plural', 'bi-list'),
            );

            $cmsRoutes->loadAuthenticated(__DIR__ . '/../../routes/cms.php');
        }

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
