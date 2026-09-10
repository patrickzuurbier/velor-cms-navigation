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
use Velor\Navigation\Repositories\Contracts\NavigationItemRepositoryInterface;
use Velor\Navigation\Resources\NavigationItemResource;

class NavigationItemController extends Controller
{
    public function __construct(
        protected ResourceIndexQueryInterface $resourceIndexQuery,
        protected NavigationItemResource $navigationItemResource,
        protected NavigationItemRepositoryInterface $navigationItemRepository,
    ) {
        $this->authorizeResource(NavigationItem::class, 'navigation_item');
    }

    public function index(Request $request, Navigation $navigation): View
    {
        return view(
            'cms.layouts.index',
            [
                'pagination' => $this->resourceIndexQuery->paginate(
                    resource: $this->navigationItemResource,
                    parent: $navigation,
                    relationship: 'navigationItems',
                    search: $request->string('search')->toString(),
                ),
                'resource' => $this->navigationItemResource,
            ]
        );
    }

    public function create(Navigation $navigation): View
    {
        return view('cms.layouts.form', [
            'resource' => $this->navigationItemResource,
        ]);
    }

    public function store(NavigationItemRequest $request, Navigation $navigation): RedirectResponse
    {
        $navigationItem = $this->navigationItemRepository->createForNavigation($navigation, $request->validated());

        return redirect()->route('navigations.navigation-items.show', [
            'navigation'      => $navigation->id,
            'navigation_item' => $navigationItem->id,
        ])->with('status', 'Navigation item created.');
    }

    public function show(Navigation $navigation, NavigationItem $navigationItem): View
    {
        $this->abortIfNavigationItemDoesNotBelongToNavigation($navigation, $navigationItem);

        return view('cms.layouts.show', [
            'resource' => $this->navigationItemResource,
        ]);
    }

    public function edit(Navigation $navigation, NavigationItem $navigationItem): View
    {
        $this->abortIfNavigationItemDoesNotBelongToNavigation($navigation, $navigationItem);

        return view('cms.layouts.form', [
            'resource' => $this->navigationItemResource,
        ]);
    }

    public function update(NavigationItemRequest $request, Navigation $navigation, NavigationItem $navigationItem): RedirectResponse
    {
        $this->abortIfNavigationItemDoesNotBelongToNavigation($navigation, $navigationItem);

        $navigationItem = $this->navigationItemRepository->update($navigationItem, $request->validated());

        return redirect()->route('navigations.navigation-items.show', [
            'navigation'      => $navigation->id,
            'navigation_item' => $navigationItem->id,
        ])->with('status', 'Navigation item updated.');
    }

    public function destroy(Navigation $navigation, NavigationItem $navigationItem): RedirectResponse
    {
        $this->abortIfNavigationItemDoesNotBelongToNavigation($navigation, $navigationItem);

        $this->navigationItemRepository->delete($navigationItem);

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
