<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeatureRequest;
use App\Models\Feature;
use App\Models\Platform;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;


class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('features.index')){
            abort(401);
        }

        return inertia('Modules/Feature/Index', [
            'features' => Feature::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? config('app.perpage'))
                ->withQueryString()
                ->through(fn($feature) => [
                    'id' => $feature->id,
                    'name' => $feature->name,
                    'price' => $feature->price,
                    'description' => $feature->description,
                    'created_at' => $feature->created_at->format('d M Y'),
                    'show_url' => URL::route('features.show', $feature->id),
                ]),
            'filters' => Request::only(['search','perPage']),
            'platforms' => Platform::all(),
            'main_url' =>  URL::route('features.index'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('features.create')){
            abort(401);
        }

        $request->validate([
            "name" => ["required", "min:3"],
            "price" => ["required", "numeric"],
            "platform_id" => ["required", "integer"],
        ]);

        Feature::create($request->validated());
        return redirect()->route('features.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feature  $feature
     * @return Feature
     */
    public function show(Feature $feature)
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('features.show')){
            abort(401);
        }

        return $feature;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(FeatureRequest $request, Feature $feature)
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('features.edit')){
            abort(401);
        }

        $feature->update($request->validated());
        return redirect()->route('features.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Feature $feature)
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('features.delete')){
            abort(401);
        }

        $feature->delete();
        return redirect()->route('features.index');
    }
}
