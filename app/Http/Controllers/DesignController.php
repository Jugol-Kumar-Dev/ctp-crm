<?php

namespace App\Http\Controllers;

use App\Http\Requests\DesignRequest;
use App\Models\Client;
use App\Models\Design;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;


class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Modules/Package/Index', [
            'packages' => Design::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? config('app.perpage'))
                ->withQueryString()
                ->through(fn($package) => [
                    'id' => $package->id,
                    'name' => $package->name,
                    'price' => $package->price,
                    'description' => nl2br($package->description) ,
                    'created_at' => $package->created_at->format('d M Y'),
                    'show_url' => URL::route('designs.show', $package->id),
                ]),
            'filters' => Request::only(['search','perPage']),
            'main_url' => URL::route('designs.index'),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DesignRequest $request)
    {
        Design::create($request->validated());
        return redirect()->route('designs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Design  $design
     * @return \Illuminate\Http\Response
     */
    public function show(Design $design)
    {
        return $design;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Design  $design
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DesignRequest $request, Design $design)
    {
        $design->update($request->validated());
        return redirect()->route('designs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Design  $design
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Design $design)
    {
        $design->delete();
        return redirect()->route('designs.index');
    }
}
