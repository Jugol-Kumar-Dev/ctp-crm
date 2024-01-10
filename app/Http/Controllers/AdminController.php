<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        if (!auth()->user()->can('user.index')){
            abort(404);
        }

        return inertia('Modules/Admin/Index', [
            'users' => User::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('email', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo,
                    'active_on' => $user->created_at->format('d M Y'),
                    'roles' => $user->getRoleNames(),
                    'show_url' => URL::route('users.show', $user->id),
                ]),
            'filters' => Request::only(['search','perPage']),
            'roles'=> Role::all(['id','name']),
            'main_url' =>  URL::route('users.index'),
        ]);


    }
    public function allUsers(){
//        if (!Request::ajax()){
//            abort(404);
//        }
        return User::where('id', '!=', Auth::id())->get();
    }

    public function create(){
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
        if (!auth()->user()->can('user.create')){
            abort(404);
        }

        Request::validate([
            'email'       => ['required','unique:users'],
            'name'   => ['required', 'string'],
            'phone'    => ['required'],
            'password'    => ['required','min:6'],
            'password_confirmation' => ['required_with:password','same:password','min:6']
        ]);

        $image_path = "";
        if (Request::hasFile('photo')){
            $image_path = Request::file('photo')->store('image', 'public');
        }



        User::create([
            'name' => Request::input('name'),
            'email' => Request::input('email'),
            'password' => bcrypt(Request::input('password')),
            'photo' => $image_path,
        ])->assignRole(Request::input('roles_name'));

        return Redirect::route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function show($id)
    {
//        if (!auth()->user()->can('user.show')){
//            abort(404);
//        }
        $user = User::findOrFail($id)->load('invoices', 'projects', 'roles');
        if(Request::input("api")){
            return $user;
        }
        return inertia('Modules/Admin/Show', [
            "user" => $user,
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
        $user = User::findOrFail($id);
        $user->update([
            'name' => Request::input('name'),
            'email' => Request::input('email'),
            'phone' => Request::input('phone'),
            'address' => Request::input('address')
        ]);
        $user->roles()->sync(Request::input('roles_name'));
        $image_path = "";
        if (Request::hasFile('photo')){
            $image_path = Request::file('photo')->store('image', 'public');
            $user->photo = $image_path;
            $user->save();
        }
        return back();
    }

    public function uploadProfile(){

        $user = User::findOrFail(Auth::id());
        if(Storage::exists($user->photo)){
            Storage::delete($user->photo);
        }
        $filePath = Request::file('image')->store('images', 'public');
        $user->photo = $filePath;
        $user->save();
        return back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function updateCredentials($id){


        $user = User::findOrFail($id);

        Request::validate([
           'name' => 'required',
           'email' => 'required',
           'password' => 'required'
        ]);

        $user->name = Request::input('name');
        $user->email = Request::input('email');
        $user->password = Hash::make(Request::integer('password'));
        $user->update();
    }


    public function destroy($id)
    {
        try{
            $user = User::findOrFail($id);
            if(count($user->invoices) | count($user->transactions) | count($user->projects) | count($user->clients) | count($user->expanses)){
                return back()->withErrors('User data exist. Can not delete this user.');
            }
            $user->delete();
            return back();
        }catch (\Exception $e){
            return back()->withErrors('error', $e->getMessage());
        }
    }
}
