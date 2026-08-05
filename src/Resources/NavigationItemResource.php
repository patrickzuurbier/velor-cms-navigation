<?php

declare(strict_types=1);

namespace Velor\Navigation\Resources;

use App\Resources\AbstractResource;
use App\Resources\Fields\Checkbox;
use App\Resources\Fields\Field;
use App\Resources\Fields\Number;
use App\Resources\Fields\Text;
use App\Resources\Tabs\ResourceTab;
use Illuminate\Contracts\Routing\UrlGenerator;
use Velor\Navigation\Models\NavigationItem;

class NavigationItemResource extends AbstractResource
{
    public static string $model = NavigationItem::class;

    public function __construct(
        protected UrlGenerator $urlGenerator,
    ) {
    }

    public function titleAttribute(): string
    {
        return 'text';
    }

    /**
     * @return array<int, Field>
     */
    public function fields(): array
    {
        return [
            Checkbox::make('is_active')
                ->label(__('velor-navigation::resources.navigation-items.fields.active'))
                ->sortable(),
            Text::make('text')
                ->label(__('velor-navigation::resources.navigation-items.fields.text'))
                ->sortable()
                ->searchable()
                ->translatable()
                ->rules([
                    'required',
                    'max:255',
                ]),

            Text::make('url')
                ->label(__('velor-navigation::resources.navigation-items.fields.url'))
                ->sortable()
                ->searchable()
                ->translatable()
                ->rules([
                    'required',
                    'max:255',
                ]),

            Number::make('sort_order')
                ->label(__('velor-navigation::resources.navigation-items.fields.order'))
                ->sortable()
                ->rules([
                    'nullable',
                    'integer',
                ]),
        ];
    }

    /**
     * @return array<int, ResourceTab>
     */
    public function tabs(): array
    {
        return [
            ResourceTab::make(__('velor-navigation::resources.navigation-items.tabs.navigation'))
                ->url(fn (NavigationItem $navigationItem, mixed $_urlGenerator): string => $this->urlGenerator->route(
                    'navigations.show',
                    ['navigation' => $navigationItem->getAttribute('navigation_id')]
                ))
                ->onlyOnIndex(),
        ];
    }
}
