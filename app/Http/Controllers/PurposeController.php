<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurposeRequest;
use App\Models\Purpose;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class PurposeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {

        if (!auth()->user()->can('purpose.index') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        return inertia('Purpose/Index', [
            'purposes' => Purpose::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? config('app.perpage'))
                ->withQueryString()
                ->through(fn($purpose) => [
                    'id' => $purpose->id,
                    'name' => $purpose->name,
                    'price' => $purpose->price,
                    'description' => $purpose->description,
                    'created_at' => $purpose->created_at->format('d M Y'),
                    'show_url' => URL::route('purposes.show', $purpose->id),
                ]),
            'filters' => Request::only(['search','perPage']),
            'main_url' => URL::route('purposes.index'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PurposeRequest $request)
    {
        if (!auth()->user()->can('purpose.create') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        Purpose::create($request->validated());
        return redirect()->route('purposes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purpose  $purpose
     * @return Purpose
     */
    public function show(Purpose $purpose)
    {
        if (!auth()->user()->can('purpose.show') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        return $purpose;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purpose  $purpose
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PurposeRequest $request, Purpose $purpose)
    {
        if (!auth()->user()->can('purpose.edit') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        $purpose->update($request->validated());
        return redirect()->route('purposes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purpose  $purpose
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Purpose $purpose)
    {
        if (!auth()->user()->can('purpose.delete') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        $purpose->delete();
        return redirect()->route('purposes.index');
    }
}
