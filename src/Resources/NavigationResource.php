<?php

declare(strict_types=1);

namespace Velor\Navigation\Resources;

use App\Resources\AbstractResource;
use App\Resources\Fields\Field;
use App\Resources\Fields\Text;
use App\Resources\Tabs\ResourceTab;
use Illuminate\Contracts\Routing\UrlGenerator;
use Velor\Navigation\Models\Navigation;

class NavigationResource extends AbstractResource
{
    public static string $model = Navigation::class;

    public function __construct(
        protected UrlGenerator $urlGenerator,
    ) {
    }

    public function titleAttribute(): string
    {
        return 'name';
    }

    /**
     * @return array<int, Field>
     */
    public function fields(): array
    {
        return [
            Text::make('name')
                ->label(__('velor-navigation::resources.navigations.fields.name'))
                ->sortable()
                ->searchable()
                ->rules(['required', 'string']),
        ];
    }

    /**
     * @return array<int, ResourceTab>
     */
    public function tabs(): array
    {
        return [
            ResourceTab::make(__('velor-navigation::resources.navigations.tabs.navigation_items'))
                ->url(fn (Navigation $navigation, mixed $_urlGenerator): string => $this->urlGenerator->route(
                    'navigations.navigation-items.index',
                    ['navigation' => $navigation->getRouteKey()]
                ))
                ->onlyOnShow(),
        ];
    }
}
