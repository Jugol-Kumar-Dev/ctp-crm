<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\User;
use App\Notifications\TodoNotefication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $todos = Todo::query()->with('user')
            ->whereNull('todo_id')
            ->when(Request::input('search'), function ($query, $search) {
                $query->where('title', 'like', "%$search%")
                    ->orWhere('about_todo', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        if (!in_array( 'Administrator', Auth::user()->getRoleNames()->toArray())) {
            $todos = $todos->filter(function ($todo){
                $user_ids = json_decode($todo['users']);
                return in_array(Auth::id(), $user_ids) || $todo['user_id'] == Auth::id();
            });
        }

        return inertia('Todo/AllTodos',[
            'users' =>  User::where('id', '!=', Auth::id())->get(),
            'todos' => ['data' => $todos],
            'main_url' => URL::route('todos.index'),
        ]);
    }


    public function myTodos(){
        $todos = Todo::query()
            ->with('user')
            ->where('user_id', Auth::id())
            ->whereNull('todo_id')
            ->when(Request::input('search'), function ($query, $serch){
                $query->where('title', 'like', "%$serch%")
                    ->orWhere('about_todo', 'like', "%{$serch}%");
            })
            ->latest()
            ->paginate(Request::input('perPage') ?? config('app.perpage'))
            ->withQueryString();



        return inertia('Todo/MyTodos',[
            'users' =>  User::where('id', '!=', Auth::id())->get(),
            'todos' => $todos,
            'main_url' => URL::route('todos.index'),
        ]);
    }

    public function completeTodos(){

        $todos = Todo::query()->with('user')
            ->whereNull('todo_id')
            ->where('priority',  '=', 'Complete')
            ->when(Request::input('search'), function ($query, $search) {
                $query->where('title', 'like', "%$search%")
                    ->orWhere('about_todo', 'like', "%{$search}%");
            })
            ->latest()
            ->get();


        return inertia('Todo/ComplateTodos',[
            'users' =>  User::where('id', '!=', Auth::id())->get(),
            'todos' => ['data' => $todos],
            'main_url' => URL::route('todos.index'),
        ]);
    }

    public function replayTodo(){
        $todo = Todo::findOrFail(Request::input('todo_id'));

        $path = null;
        if (Request::hasFile('file')) {
            $fileName = Request::file('file')->getClientOriginalName();
//            $path = Storage::putFileAs('public/todos', Request::file('file'), $fileName);
            $path = Request::file('file')->store('project', 'public');
        }

        $todo = Todo::create([
            'title' => Request::input('title'),
            'users' => $todo->users,
            'date' => now()->format('d-y-m h:m:s'),
            'about_todo' => Request::input('message'),
            'file' => $path,
            'user_id' => Auth::id(),
            'priority' => null,
            'todo_id' => $todo->id,
        ]);

        $todo->load('user');
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'Administrator');
        })->pluck('id');

        $ids = array_filter(json_decode($todo->users), function($value) { return $value !== Auth::id(); });

        $authId = Auth::id();
        $mergedArray = array_merge($ids, $admins->toArray());
        if (in_array($authId, $mergedArray)) {
            $mergedArray = array_values(array_diff($mergedArray, [$authId]));
        }
        $users = User::whereIn('id', $mergedArray)->get();
        $todo->type = 'Replay';
        $todo->load('user');
        Notification::send($users, new TodoNotefication($todo));
        return back();

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

    public function readAllNotification(){
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        Request::validate([
            'title' => 'required',
        ]);

        if(Request::input('allUsers') != true || Request::input('users')){
            Request::validate([
                'users' => 'required',
            ]);
        }

        $userId = [];
        if (Request::input('allUsers')){
            $userId = User::pluck('id');
        }else{
            $userId = Request::input('users');
        }


//        if (Request::input('date')){
//            Request::validate([
//                'date' => 'date'
//            ]);
//        }


        $path = null;
        if (Request::hasFile('attachment')) {
            $fileName = Request::file('attachment')->getClientOriginalName();

//            Storage::disk('public')->delete($project->files);
            $path = Request::file('attachment')->store('project', 'public');
//            $path = Storage::putFileAs('public/todos', Request::file('attachment'), $fileName);
        }

        $todo = Todo::create([
           'title' => Request::input('title'),
           'users' => json_encode($userId),
           'date' => now()->format('d-y-m h:m:s'),
           'about_todo' => Request::input('aboutTodo'),
           'file' => $path,
           'user_id' => Auth::id(),
           'priority' => Request::input('priority') ?? 'Normal',
        ]);

        $ids = array_filter(json_decode($todo->users), function($value) { return $value !== Auth::id(); });
        $users = User::whereIn('id', $ids)->get();
        $todo->type = 'Create';
        $todo->load('user');
        Notification::send($users, new TodoNotefication($todo));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object
     */
    public function show($id)
    {
        $todo = Todo::where('id', $id)->first();

        if ($todo){
            if (Request::input('status')){
                $todo->priority = Request::input('status');
                $todo->update();

                $ids = array_filter(json_decode($todo->users), function($value) { return $value !== Auth::id(); });
                $users = User::whereIn('id', $ids)->get();
                $todo->type = 'Change Status';
                $todo->load('user');
                Notification::send($users, new TodoNotefication($todo));
                return back();
            }

            $todo->downloadUrl = $todo->file ? Storage::url($todo->file) : null;
//            $todo->downloadFile = $todo->file ? Storage::download($todo->file) : null;

            if (Request::input('show_data') == 'true'){
                if (Request::input('notification_id')){
                    DB::table('notifications')->where('id', Request::input('notification_id'))->delete();
                }
                $replais = Todo::query()->where('todo_id', $id)->with('user')->get();
                $replais->map(function ($todo){
                    $todo->downloadUrl = $todo->file ? Storage::url($todo->file) : null;
                    return $todo;
                });

                return inertia('Todo/SingleTodo',[
                    'todo' => $todo->load('user'),
                    'replays' => $replais,
                    'main_url' => URL::route('todos.index'),
                ]);
            }
        }else{
            if (Request::input('notification_id')){
                DB::table('notifications')->where('id', Request::input('notification_id'))->delete();
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {

        $todo = Todo::findOrFail($id);

// Delete the main "todo" record and its related "replay" records
        $todo->replayTodos()->delete();
        $todo->delete();

// Delete the file associated with the main "todo" record
        if ($todo->file && Storage::exists($todo->file)) {
            Storage::delete($todo->file);
        }

        return back();
    }
}
