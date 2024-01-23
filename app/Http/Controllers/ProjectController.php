<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CustomInvoice;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {

        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('project.index')){
            abort(401);
        }
        return inertia('Projects/Index', [
            'projects' => Project::query()
                ->with(['user', 'users', 'client', 'invoice'])
                ->latest()
                ->when(Request::input('search'), function ($query, $search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($client) use($search){
                        $client
                            ->where('name',    'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                        ;
                    })
                    ->orWhereHas('user', function ($user) use($search){
                        $user->where('name',    'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('users', function ($developer) use($search){
                        $developer->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($project) => [
                    'id'            => $project->id,
                    'project'       => $project,
                    'creator'       => $project->user ? $project->user->name : "asdfasdf",
                    'project_date'  => $project->date->format('d M Y'),
                    'start_date'    => $project->start->format('d M'),
                    'end_date'      => $project->end->format('d M Y'),
                    'create_at'     => $project->created_at->format('d M Y'),
                    "edit_url"      => URL::route('projects.edit', $project->id),
                    "show_url"      => URL::route('projects.show', $project->id),
                ]),
            'clients'  => Client::all(['id','name', 'email', 'phone']),
            'users'    => User::all(['id','name', 'email', 'photo']),
            'invoice' => Invoice::with(['quotation', 'client'])->get(),
            'filters'  => Request::only(['search','perPage']),
            'main_url' => URL::route('projects.index'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('project.create')){
            abort(401);
        }
        return inertia('Projects/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function createArrayGroups($items)
    {
        $added = array();
        foreach($items as $key=> $item){
            $added[$key] = [
                "user_id" => $item
            ];
        }
        return $added;
    }

    public function store(Request $request)
    {


        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('project.create')){
            abort(401);
        }

        Request::validate([
           'name' => 'required',
           'date' => 'required',
           'start_date' => 'required',
           'end_date' => 'required',
            'url' => 'nullable|string'
        ]);

        if(empty(Request::input('invoiceId')) && empty(Request::input('clientId'))){
            Request::validate([
                'invoiceId' => 'required|integer',
                'clientId' => 'required|integer',
            ],[
                'invoiceId.required' => 'Invoice Id Or Client Id Is Required',
                'clientId.required' => 'Invoice Id Or Client Id Is Required'
            ]);
        }

        $filePath= NULL;
        if (Request::hasFile('files')) {
//            $filePath = Storage::putFile('/project', Request::file('files'));

//            Storage::disk('public')->delete($project->files);
            $filePath = Request::file('files')->store('project', 'public');
        }

        $project = Project::create([
            "name"        => Request::input('name'),
            "invoice_id"  => Request::input('invoiceId'),
            "user_id"     => Auth::id(),
            "client_id"   => Request::input('clientId'),
            "date"        => Request::input('date'),
            "start"       => Request::input('start_date'),
            "end"         => Request::input('end_date'),
            "description" => Request::input('project_details'),
            "credential"  => Request::input('credintials'),
            "status"      => Request::input('status') ?? 'New Project',
            "files"       => $filePath,
        ]);


        $project->clients()->sync(Request::all('clientId'));
        $agents = Request::input('agents');
        if ($agents && count($agents)) {
            $project->users()->sync($this->createArrayGroups($agents));
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function show($id)
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('project.show')){
            abort(401);
        }

        $project = Project::with(['user', 'users', 'users','users.roles', 'clients',
            'client', 'invoice', 'invoice.client',
            'invoice.transactions.method:id,name', 'invoice.transactions.receivedBy:id,name',
            'invoice.transactions.paymentBy:id,name', 'invoice.quotation', 'invoice.user'])->findOrFail($id);

//        $project->users->map(function ($user){
//           return $user->role = $user->getRoleNames();
//        });



        if(auth()->user()->hasRole('administrator')  || auth()->user()->can('invoice.show')) {
            $invObj = new InvoiceController();
            $pref = $invObj->invoiceItemsGenerate($project->invoice);
        }



        $fileInfo = [];
        if(file_exists('storage/'.$project->files)){
            $fileInfo = stat('storage/'.$project->files);
        }


        return inertia('Projects/Show', [
            "info" =>  $project,
            "pref" => $pref ?? [],
            "users" => User::with('roles')->get(),
            "dates" =>[
                "end_date" => $project->end->format("d M, y"),
                "start_date" => $project->start->format("d M, y"),
                "created_at" => $project->created_at->diffForHumans(),
            ],
            "urls" =>[
                "main_url" => URL::route('projects.index'),
                "assign_url" => URL::route('projects.assignDevelopers'),
                "remove_user" => URL::route('projects.removeUser'),
                "update_status" => URL::route('projects.updateProgress')
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function edit($id)
    {

        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('project.edit')){
            abort(401);
        }
        return Project::with(['user', 'users', 'clients', 'client'])->findOrFail($id);

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


        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('project.edit')){
            abort(401);
        }
        $project = Project::findOrFail($id);
        $filePath = "";

        if (Request::hasFile('files')) {


//            Storage::disk('public')->delete($project->files);
            $filePath = Request::file('files')->store('project', 'public');//            $filePath = Request::file('files')->store('image', 'public');

            $project->files = $filePath;
            $project->save();
        }

        $project->update([
            "name"        => Request::input('name'),
            "user_id"     => Auth::id(),
            "client_id"   => Request::input('client_id'),
            "date"        => Request::input('date'),
            "start"       => Request::input('start_date'),
            "end"         => Request::input('end_date'),
            "description" => Request::input('project_details'),
            "credential"  => Request::input('credintials'),
            "status"      => Request::input('status'),
        ]);


        $project->clients()->sync(Request::all('client_id'));
        $agents = Request::input('agents');
        if (count($agents)) {
            $project->users()->sync($this->createArrayGroups($agents));
        }

    }

    public function updateProjectDetails($id){


        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('project.edit')){
            abort(401);
        }
        $project = Project::findOrFail($id);
        $project->update([
            "date"        => Request::input('date'),
            "start"       => Request::input('start_date'),
            "end"         => Request::input('end_date'),
            "description" => Request::input('project_details'),
            "credential"  => Request::input('credintials'),
        ]);

        return back();
    }

    public function updateProjectBackup($id){
        Request::validate([
           'files' => 'required'
        ]);

        $project = Project::findOrFail($id);
        $project->backup = json_encode(Request::input('files'));
        $project->save();
        return back();
    }
    public function updateProjectAttachment($id){


        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('project.attachment')){
            abort(401);
        }

        $project = Project::findOrFail($id);
        if (Request::hasFile('files')) {
            Storage::disk('public')->delete($project->files);
            $filePath = Request::file('files')->store('project', 'public');
//            $filePath = Storage::putFile('project', Request::file('files'));
            $project->files = $filePath;
            $project->save();
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {

        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('project.delete')){
            abort(401);
        }
        $project = Project::findOrFail($id);
        if(Storage::exists($project->files)){
            Storage::delete($project->files);
        }
        $project->delete();
        return  back();
    }

    public function assignDevelopers(){

        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('project.edit')){
            abort(401);
        }
        $project = Project::findOrFail(Request::input('projectId'));
        $project->users()->sync(Request::input('users'));
        return back();
    }

    public function removeUser(){

        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('project.edit')){
            abort(401);
        }


        $query = Request::only(['project_id', 'user_id']);
            if ($query){
                $project = Project::with('users')->findOrFail($query["project_id"]);
                $user = $project->users->find($query['user_id']);
                $project->users()->detach($user);// $user->delete();
                return back();
            }
        return back();
    }

    public function updateProgress(){


        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('project.edit')){
            abort(401);
        }

        $project = Project::findOrFail(Request::input('projectId'));
        $project->status = Request::input('status');
        $project->progress = Request::input('progressData');
        $project->update();
        return back();
    }

    public function employeeProjects(){

        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('project.employees')){
            abort(401);
        }


        return inertia('Projects/EmployeeProjects',[
            'projects' => Project::query()
                ->with(['client', 'client', 'users'])
                ->latest()
                ->whereHas('users', function($user){
                    $user->where('user_id', Auth::id());
//                    $user->id = Auth::id();
                })
                ->when(Request::input('search'), function ($query, $search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('client', function ($client) use($search){
                            $client
                                ->where('name',    'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                            ;
                        })
                        ->orWhereHas('user', function ($user) use($search){
                            $user->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('users', function ($developer) use($search){
                            $developer->where('name', 'like', "%{$search}%");
                        });
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($project) => [
                    'id'            => $project->id,
                    'project'       => $project,
                    'creator'       => $project->user ? $project->user->name : "asdfasdf",
                    'project_date'  => $project->date->format('d M Y'),
                    'start_date'    => $project->start->format('d M'),
                    'end_date'      => $project->end->format('d M Y'),
                    'create_at'     => $project->created_at->format('d M Y'),
                    "edit_url"      => URL::route('projects.edit', $project->id),
                    "show_url"      => URL::route('projects.show', $project->id),
                ]),
            'clients'  => Client::all(['id','name', 'email', 'phone']),
            'users'    => User::all(['id','name', 'email']),
            'invoice' => Invoice::with(['quotation', 'client'])->get(),
            'filters'  => Request::only(['search','perPage']),
            'emp_url' => URL::route('employeeProject'),
            'main_url' => URL::route('projects.index'),
        ]);
    }



}
