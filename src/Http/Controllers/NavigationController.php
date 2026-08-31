<?php

declare(strict_types=1);

namespace Velor\Navigation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Resources\Contracts\ResourceIndexQueryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Velor\Navigation\Http\Requests\NavigationRequest;
use Velor\Navigation\Models\Navigation;
use Velor\Navigation\Resources\NavigationResource;

class NavigationController extends Controller
{
    public function __construct(
        protected ResourceIndexQueryInterface $resourceIndexQuery,
        protected NavigationResource $navigationResource,
    ) {
        $this->authorizeResource(Navigation::class);
    }

    public function index(Request $request): View
    {
        return view(
            'cms.layouts.index',
            [
                'pagination' => $this->resourceIndexQuery->paginate(
                    resource: $this->navigationResource,
                    search: $request->string('search')->toString(),
                ),
                'resource' => $this->navigationResource,
            ]
        );
    }

    public function create(): View
    {
        return view('cms.layouts.form', [
            'resource' => $this->navigationResource,
        ]);
    }

    public function store(NavigationRequest $request): RedirectResponse
    {
        $navigation = Navigation::create($request->validated());

        return redirect()
            ->route('navigations.show', ['navigation' => $navigation->id])
            ->with('status', 'Navigation created.');
    }

    public function show(Navigation $navigation): View
    {
        return view('cms.layouts.show', [
            'resource' => $this->navigationResource,
        ]);
    }

    public function edit(Navigation $navigation): View
    {
        return view('cms.layouts.form', [
            'resource' => $this->navigationResource,
        ]);
    }

    public function update(NavigationRequest $request, Navigation $navigation): RedirectResponse
    {
        $navigation->update($request->validated());

        return redirect()
            ->route('navigations.show', ['navigation' => $navigation->id])
            ->with('status', 'Navigation updated.');
    }

    public function destroy(Navigation $navigation): RedirectResponse
    {
        $navigation->delete();

        return redirect()
            ->route('navigations.index')
            ->with('status', 'Navigation deleted.');
    }
}
