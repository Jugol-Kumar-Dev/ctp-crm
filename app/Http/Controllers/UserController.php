<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\Mime\Header\all;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {

        return inertia('Modules/Users/Index', [
            'users' => User::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('email', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? config('app.perpage'))
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->username,
                    'email' => $user->email,
                    'photo' => $user->photo,
                    'active_on' => $user ? $user->created_at ? $user->created_at->format('d M Y') : '' : "",
                    'role' => $user->getRoleNames(),
                ]),

            'filters' => Request::only(['search','perPage'])
        ]);







//        return Inertia::render('Modules/Users/Index', [
//            'users' => UserResource::collection(User::all())
//        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Modules/Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @param $users
     * @return \Inertia\Response
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());

        return Inertia::render('Modules/Users/Create', [
           'user' => new UserResource($user),
            'notification' => [
                'success'  => true,
                'status'   => Response::HTTP_CREATED,
                'message'  => 'This User Created Successfully Done!'
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        return dd($id);

        return "ok";
    }
}
