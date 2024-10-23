<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServicesRequest;
use App\Http\Requests\WorkRequest;
use App\Models\Design;
use App\Models\Website;
use App\Models\Work;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;


class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {

        return inertia('Modules/Services/Index', [
            'services' => Website::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? config('app.perpage'))
                ->withQueryString()
                ->through(fn($service) => [
                    'id' => $service->id,
                    'name' => $service->name,
                    'price' => $service->price,
                    'description' => $service->description,
                    'created_at' => $service->created_at->format('d M Y'),
                    'show_url' => URL::route('services.show', $service->id),
                ]),
            'filters' => Request::only(['search','perPage']),
            'main_url' => URL::route('services.index'),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    public function store(ServicesRequest $request)
    {
        Website::create($request->validated());
        return redirect()->route('services.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Website $website
     * @return Design|Website
     */
    public function show($id)
    {
        return Website::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Website $website
     * @return RedirectResponse
     */
    public function update(ServicesRequest $request,  $id)
    {
        $website = Website::findOrFail($id);
        $website->update($request->validated());
        return redirect()->route('services.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Website $website
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        Website::findOrFail($id)->delete();
        return redirect()->route('services.index');
    }
}
