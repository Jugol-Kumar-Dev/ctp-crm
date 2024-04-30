<?php

namespace App\Http\Middleware;

use App\Models\User;
use Inertia\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $user = Auth::user();

        if (Auth::check()){
            $user->load('roles.permissions');
            $can = $user->roles->flatMap->permissions->pluck('name')->unique()->toArray();

//            $roles_permissions = array_map(function ($item){
//                return $item['name'];
//            },
//                $user->getPermissionsViaRoles()->toArray());
//            $can = array_diff($roles_permissions, $user->getRoleNames()->toArray());
        }





        return array_merge(parent::share($request), [
            'auth' =>  $user ? [
                'user' => [
                    'id' => $user->id,
                    'username' => $user->name,
                    'role' => $user->getRoleNames(),
                    'photo' => $user->photo,
                    'can' => $can ?? [],
                    'notifications' =>$user->notifications()->where('read_at', '=', NULL)->latest()->take(10)->get()
                ],
                'ADMIN_URL' => config('app.url').'/admin',
                'MAIN_URL' => config('app.url'),
            ] : null
        ]);
    }
}
