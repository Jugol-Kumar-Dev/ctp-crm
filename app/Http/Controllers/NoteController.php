<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NoteCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Mockery\Matcher\Not;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {


        if (!auth()->user()->hasRole('Administrator')){
            $show =  auth()->user()->hasRole('Administrator')  || !auth()->user()->can('note.index');
            $my =  auth()->user()->hasRole('Administrator')  || !auth()->user()->can('note.ownonly');
            if($show && $my){
                abort(401);
            }
        }


        return inertia('Notes/Notes', [
            $search = Request::input('search'),
            'notes' => Note::query()
                ->with('noteCategory')
                ->when(!Auth::user()->hasRole('Administrator') && auth()->user()->can('note.ownonly'), function($query){
                    $query->where(function ($query) {
                        $query->whereHas('users', function ($query) {
                                $query->where('users.id', Auth::id());
                            });
                    });
                })
                ->when(Request::input('search'), function ($query, $search) {
                    $query
                        ->where('title', 'like', "%{$search}%")
                        ->orWhereHas('notes', function ($developer) use($search){
                            $developer->where('title', 'like', "%{$search}%");
                        });
                })
                ->latest()
                ->paginate(Request::input('perPage') ?? config('app.perpage'))
                ->withQueryString()
                ->through(fn($note) => [
                    'id' => $note->id,
                    'title' => $note->title,
                    'notes_cat' => $note->noteCategory,
                    'created_at' => $note->created_at->format('d M Y'),
                    'show_url' => URL::route('notes.show', $note->id),
                ]),
            'filters' => Request::only(['search','perPage', 'dateRange']),
            "main_url" => Url::route('notes.index'),
            "users" => User::all(),
            "categories" => NoteCategory::all(),
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('note.create') || auth()->user()->hasRole('administrator')){
            abort(401);
        }
        Request::validate([
            "title" =>  "required",
            "notes" => "required",
            "category" => "required|integer",
            "agents" => "nullable|array"
        ]);



        $note = Note::create([
            'title' => Request::input('title'),
            'note_category_id' => Request::input('category'),
            'notes' => Request::input('notes'),
            'status' => Request::input('status') === "true",
        ]);

        $note->users()->attach([...Request::input('agents'), Auth::id()]);
        return back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function show($id)
    {


        if (!auth()->user()->can('note.show') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        $note = Note::with(["noteCategory", "users"])->findOrFail($id);
        if(Request::input("satus") === 'edit'){
            return inertia('Notes/Edit', [
                "note" => $note,
                "users" => User::all(),
                "categories" => NoteCategory::all(),
                "update_url" => URL::route('notes.update')
            ]);
        }else{
            return inertia('Notes/Show', [
                'note' => $note
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update()
    {


        if (!auth()->user()->can('note.edit') || auth()->user()->hasRole('administrator')){
            abort(401);
        }
        if (!auth()->user()->can('note.edit')) {
            abort(401 );
        }


        $note = Note::findOrFail(Request::input('noteId'));

        $agents = [];
        foreach (Request::input("agents") as $item){
            if (is_int($item)){
                $agents[] = $item;
            }else{
                $agents[] = $item["id"];
            }
        }


        $note->update([
            'title' => Request::input('title'),
            'note_category_id' => Request::input('category')["id"] ?? Request::input('category'),
            'notes' => Request::input('notes'),
            'status' => Request::input("status"),
        ]);

        $note->users()->sync($agents);

        return redirect()->route('notes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        if (!auth()->user()->can('note.delete') || auth()->user()->hasRole('administrator')){
            abort(401);
        }
        Note::findOrFail($id)->delete();
        return back();
    }


    public function employeeNotes(){

        return inertia('Notes/EmployeeNotes', [
            'notes' => Note::with(['noteCategory', 'users'])->get()
        ]);
    }


}
