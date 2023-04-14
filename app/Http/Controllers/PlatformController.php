<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlatformRequest;
use App\Models\Design;
use App\Models\Platform;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;


class PlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {

        return inertia('Platforms/Index', [
            'platforms' => Platform::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($platform) => [
                    'id' => $platform->id,
                    'name' => $platform->name,
                    'featureds' => json_decode($platform->featureds),
                    'created_at' => $platform->created_at->format('d M Y'),
                ]),
            'filters' => Request::only(['search','perPage']),
            'main_url' => URL::route('platforms.index'),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */

    public function create(){
        return inertia('Platforms/Store', [
            'main_url' => URL::route('platforms.index')
        ]);
    }


    public function store()
    {

        Platform::create([
            'name' => Request::input('platform'),
            'featureds' => json_encode(Request::all("features")['features']),
            'status' => true,
        ]);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Platform  $platform
     * @return Platform
     */
    public function show(Platform $platform)
    {
        return $platform;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Platform  $platform
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PlatformRequest $request, Platform $platform)
    {
        $platform->update($request->validated());
        return redirect()->route('platforms.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Platform  $platform
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Platform $platform)
    {
        $platform->delete();
        return redirect()->route('platforms.index');
    }
}
