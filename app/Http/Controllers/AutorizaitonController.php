<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use NumberToWords\Language\Persian\PersianConverter;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AutorizaitonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        if (!auth()->user()->can('authorization.index') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        $roles = Role::with(["users"])->select('id', 'name', 'is_delete')
            ->withCount("users")->get();

        $roles->map(function ($item){
           $item->is_delete = $item->is_delete == 0 ? false : true;
           return $item;
        });


        return inertia('Roles/Index', [
            'permissions' => Permission::with(['roles', 'users'])->get()->groupBy('module_name'),
            'all_permissions' => Permission::with("roles", "users")->get(),
            'roles'       => $roles,
            'can'         => null,
            'create_url'  => URL::route('authorizations.store'),
            'main_url'  => URL::route('authorizations.index')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
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
    public function store()
    {

        if (!auth()->user()->can('authorization.create') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        Request::validate([
           'roleName' => 'required|unique:roles,name',
           'selectedPermissions' => 'required'
        ]);

        $role = Role::create(['name' => Request::input('roleName')]);

        foreach (Request::input('selectedPermissions') as $permission) {
            $role->givePermissionTo($permission);
        };

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return $id;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {

        if (!auth()->user()->can('authorization.edit') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        return Response::json([
            'permissions' => Permission::with(['roles', 'users'])->get()->groupBy('module_name'),
            'edited' => Role::with('permissions')->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('authorization.edit') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        Request::validate([
            "roleName" => ['required', Rule::unique('roles', 'name')->ignore($id)],
            'selectedPermissions' => 'required'
        ]);

        $role = Role::findOrFail($id);

        $role->update(['name' => Request::input('roleName')]);
        $role->syncPermissions(Request::input('selectedPermissions'));

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
        if (!auth()->user()->can('authorization.delete') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        $role = Role::findById($id);
        $role->delete();
        return back();

    }
}
