<?php

declare(strict_types=1);

use Velor\Navigation\Models\Navigation;
use Velor\Navigation\Models\NavigationItem;
use Velor\Navigation\Policies\NavigationItemPolicy;
use Velor\Navigation\Policies\NavigationPolicy;
use Velor\Navigation\Resources\NavigationItemResource;
use Velor\Navigation\Resources\NavigationResource;

return [
    'resources' => [
        'navigation'      => NavigationResource::class,
        'navigation_item' => NavigationItemResource::class,
    ],

    'policies' => [
        Navigation::class     => NavigationPolicy::class,
        NavigationItem::class => NavigationItemPolicy::class,
    ],
];
