<?php

declare(strict_types=1);

namespace Velor\Navigation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Resources\Contracts\ResourceIndexQueryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Velor\Navigation\Http\Requests\NavigationItemRequest;
use Velor\Navigation\Models\Navigation;
use Velor\Navigation\Models\NavigationItem;

class NavigationItemController extends Controller
{
    public function __construct(
        protected ResourceIndexQueryInterface $resourceIndexQuery,
    ) {
        $this->authorizeResource(NavigationItem::class, 'navigation_item');
    }

    public function index(Request $request, Navigation $navigation): View
    {
        return view(
            'cms.layouts.index',
            [
                'pagination' => $this->resourceIndexQuery->paginate(
                    model: NavigationItem::class,
                    parent: $navigation,
                    relationship: 'navigationItems',
                    search: $request->string('search')->toString(),
                ),
                'model' => new NavigationItem([
                    'navigation_id' => $navigation->getKey(),
                ]),
            ]
        );
    }

    public function create(Navigation $navigation): View
    {
        return view('cms.layouts.form', [
            'model' => new NavigationItem([
                'navigation_id' => $navigation->getKey(),
            ]),
        ]);
    }

    public function store(NavigationItemRequest $request, Navigation $navigation): RedirectResponse
    {
        $navigationItem = $navigation->navigationItems()->create($request->validated());

        return redirect()->route('navigations.navigation-items.show', [
            'navigation'      => $navigation->id,
            'navigation_item' => $navigationItem->id,
        ])->with('status', 'Navigation item created.');
    }

    public function show(Navigation $navigation, NavigationItem $navigationItem): View
    {
        $this->abortIfNavigationItemDoesNotBelongToNavigation($navigation, $navigationItem);

        return view('cms.layouts.show', [
            'model' => $navigationItem,
        ]);
    }

    public function edit(Navigation $navigation, NavigationItem $navigationItem): View
    {
        $this->abortIfNavigationItemDoesNotBelongToNavigation($navigation, $navigationItem);

        return view('cms.layouts.form', [
            'model' => $navigationItem,
        ]);
    }

    public function update(NavigationItemRequest $request, Navigation $navigation, NavigationItem $navigationItem): RedirectResponse
    {
        $this->abortIfNavigationItemDoesNotBelongToNavigation($navigation, $navigationItem);

        $navigationItem->update($request->validated());

        return redirect()->route('navigations.navigation-items.show', [
            'navigation'      => $navigation->id,
            'navigation_item' => $navigationItem->id,
        ])->with('status', 'Navigation item updated.');
    }

    public function destroy(Navigation $navigation, NavigationItem $navigationItem): RedirectResponse
    {
        $this->abortIfNavigationItemDoesNotBelongToNavigation($navigation, $navigationItem);

        $navigationItem->delete();

        return redirect()
            ->route('navigations.navigation-items.index', ['navigation' => $navigation->id])
            ->with('status', 'Navigation item deleted.');
    }

    protected function abortIfNavigationItemDoesNotBelongToNavigation(Navigation $navigation, NavigationItem $navigationItem): void
    {
        if ((string) $navigationItem->getAttribute('navigation_id') === (string) $navigation->getKey()) {
            return;
        }

        abort(404);
    }
}
