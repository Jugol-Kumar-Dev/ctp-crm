<?php

namespace App\Http\Controllers;

use App\Models\NoteCategory;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class NoteCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {



        if (!auth()->user()->can('note.category') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        return inertia('Notes/Category', [
            $search = Request::input('search'),
            'categories' => NoteCategory::query()
                ->with('notes')
                ->withCount('notes')
                ->when(Request::input('search'), function ($query, $search) {
                    $query
                        ->where('title', 'like', "%{$search}%")
                        ->orWhereHas('notes', function ($developer) use($search){
                            $developer->where('title', 'like', "%{$search}%");
                        });
                    ;
                })
                ->latest()
                ->paginate(Request::input('perPage') ?? config('app.perpage'))
                ->withQueryString()
                ->through(fn($note) => [
                    'id' => $note->id,
                    'title' => $note->title,
                    'notes_count' => $note->notes_count,
                    'created_at' => $note->created_at->format('d M Y'),
                    'show_url' => URL::route('notes-category.show', $note->id),
                ]),
            'filters' => Request::only(['search','perPage', 'dateRange']),
            "main_url" => Url::route('notes-category.index'),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        NoteCategory::create(
            Request::validate([
                'title' => 'required'
            ])
        );
        return back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NoteCategory  $noteCategory
     * @return \Illuminate\Http\Response
     */
    public function show(NoteCategory $noteCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NoteCategory  $noteCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(NoteCategory $noteCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NoteCategory  $noteCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NoteCategory $noteCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NoteCategory  $noteCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        NoteCategory::findOrFail($id)->delete();
        return back();
    }
}
