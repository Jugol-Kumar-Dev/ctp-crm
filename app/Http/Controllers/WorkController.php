<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkRequest;
use App\Models\Website;
use App\Models\Work;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Response;
use Inertia\ResponseFactory;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response|ResponseFactory
     */
    public function index()
    {

        return inertia('Modules/Works/Index', [
            'works' => Work::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? config('app.perpage'))
                ->withQueryString()
                ->through(fn($work) => [
                    'id' => $work->id,
                    'name' => $work->name,
                    'price' => $work->price,
                    'description' => $work->description,
                    'created_at' => $work->created_at->format('d M Y'),
                    'show_url' => URL::route('works.show', $work->id),
                ]),
            'filters' => Request::only(['search','perPage']),
            'main_url' =>  URL::route('works.index'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param WorkRequest $request
     * @return RedirectResponse
     */
    public function store(WorkRequest $request)
    {
        Work::create($request->validated());
        return redirect()->route('works.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Work $work
     * @return Work
     */
    public function show(Work $work)
    {
        return $work;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param WorkRequest $request
     * @param Work $work
     * @return RedirectResponse
     */
    public function update(WorkRequest $request, Work $work)
    {
        $work->update($request->validated());
        return redirect()->route('works.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Work $work
     * @return RedirectResponse
     */
    public function destroy(Work $work)
    {
        $work->delete();
        return redirect()->route('works.index');
    }
}
