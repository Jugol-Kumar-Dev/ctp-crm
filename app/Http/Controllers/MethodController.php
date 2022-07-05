<?php

namespace App\Http\Controllers;

use App\Http\Requests\MethodRequest;
use App\Models\Method;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class MethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {

        return inertia('Modules/Methods/Index', [
            'moethods' => Method::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($method) => [
                    'id' => $method->id,
                    'name' => $method->name,
                    'created_at' => $method->created_at->format('d M Y'),
                    'show_url' => URL::route('methods.show', $method->id),
                ]),
            'filters' => Request::only(['search','perPage']),
            'main_url' => URL::route('methods.index'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MethodRequest $request)
    {
        Method::create($request->validated());
        return redirect()->route('methods.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Method  $method
     * @return Method
     */
    public function show(Method $method)
    {
        return $method;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Method  $method
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MethodRequest $request, Method $method)
    {
        $method->update($request->validated());
        return redirect()->route('methods.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Method  $method
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Method $method)
    {
        $method->delete();
        return redirect()->route('methods.index');
    }
}
