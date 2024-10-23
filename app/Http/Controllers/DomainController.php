<?php

namespace App\Http\Controllers;

use App\Http\Requests\DomainRequest;
use App\Http\Requests\UpdateDomain;
use App\Models\Domain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;


class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Modules/Domains/Index', [
            'domains' => Domain::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? config('app.perpage'))
                ->withQueryString()
                ->through(fn($domain) => [
                    'id' => $domain->id,
                    'name' => $domain->name,
                    'price' => $domain->price,
                    'description' => $domain->description,
                    'created_at' => $domain->created_at->format('d M Y'),
                    'show_url' => URL::route('domains.show', $domain->id),
                ]),
            'filters' => Request::only(['search','perPage']),
            'main_url' => URL::route('domains.index'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DomainRequest $request
     * @return RedirectResponse
     */
    public function store(DomainRequest $request)
    {
        Domain::create($request->validated());
        return redirect()->route('domains.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain)
    {
        return $domain;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Domain  $domain
     * @return RedirectResponse
     */
    public function update(UpdateDomain $request, Domain $domain)
    {
        $domain->update($request->validated());
        return redirect()->route('domains.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Domain  $domain
     * @return RedirectResponse
     */
    public function destroy(Domain $domain)
    {
        $domain->delete();
        return redirect()->route('domains.index');
    }
}
