<?php

namespace App\Http\Controllers;

use App\Http\Requests\HostingsRequest;
use App\Models\Hosting;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class HostingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {

        return inertia('Modules/Hostings/Index', [
            'hostings' => Hosting::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? config('app.perpage'))
                ->withQueryString()
                ->through(fn($hosting) => [
                    'id' => $hosting->id,
                    'name' => $hosting->name,
                    'price' => $hosting->price,
                    'description' => $hosting->description,
                    'created_at' => $hosting->created_at->format('d M Y'),
                    'show_url' => URL::route('hostings.show', $hosting->id),
                ]),
            'filters' => Request::only(['search','perPage']),
            'main_url' => URL::route('hostings.index'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(HostingsRequest $request)
    {
        Hosting::create($request->validated());
        return redirect()->route('hostings.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hosting  $hosting
     * @return Hosting
     */
    public function show(Hosting $hosting)
    {
        return $hosting;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hosting  $hosting
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(HostingsRequest $request, Hosting $hosting)
    {
        $hosting->update($request->validated());
        return redirect()->route('hostings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hosting  $hosting
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Hosting $hosting)
    {
        $hosting->delete();
        return redirect()->route('hostings.index');
    }
}
