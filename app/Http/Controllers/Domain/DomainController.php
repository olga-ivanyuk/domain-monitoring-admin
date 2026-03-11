<?php

namespace App\Http\Controllers\Domain;

use App\Http\Controllers\Controller;
use App\Http\Requests\Domain\StoreDomainRequest;
use App\Http\Requests\Domain\UpdateDomainRequest;
use App\Models\Domain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainController extends Controller
{
    private const int PER_PAGE = 10;

    public function __construct()
    {
        $this->authorizeResource(Domain::class, 'domain');
    }

    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $domains = $request->user()
            ->domains()
            ->with('latestCheck')
            ->latest()
            ->paginate(self::PER_PAGE);

        return view('domains.index', compact('domains'));
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('domains.create');
    }

    /**
     * @param StoreDomainRequest $request
     * @return RedirectResponse
     */
    public function store(StoreDomainRequest $request): RedirectResponse
    {
        $request->user()->domains()->create($request->validated());

        return redirect()
            ->route('domains.index')
            ->with('status', 'domain-created');
    }

    /**
     * @param Domain $domain
     * @return View
     */
    public function edit(Domain $domain): View
    {
        return view('domains.edit', compact('domain'));
    }

    /**
     * @param UpdateDomainRequest $request
     * @param Domain $domain
     * @return RedirectResponse
     */
    public function update(UpdateDomainRequest $request, Domain $domain): RedirectResponse
    {
        $domain->update($request->validated());

        return redirect()
            ->route('domains.index')
            ->with('status', 'domain-updated');
    }

    /**
     * @param Domain $domain
     * @return RedirectResponse
     */
    public function destroy(Domain $domain): RedirectResponse
    {
        $domain->delete();

        return redirect()
            ->route('domains.index')
            ->with('status', 'domain-deleted');
    }

    public function checks(Domain $domain): View
    {
        $this->authorize('view', $domain);

        $checks = $domain->checks()
            ->latest('checked_at')
            ->paginate(20);

        return view('domains.checks', compact('domain', 'checks'));
    }
}
