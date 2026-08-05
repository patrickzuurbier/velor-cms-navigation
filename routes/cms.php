<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use Velor\Navigation\Http\Controllers\NavigationController;
use Velor\Navigation\Http\Controllers\NavigationItemController;

/**
 * @var Router $router
 */
$router->resources(['navigations' => NavigationController::class]);
$router->resources(['navigations.navigation-items' => NavigationItemController::class]);
