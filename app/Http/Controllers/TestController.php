<?php

namespace App\Http\Controllers;

use App\Mail\QuotationMail;
use App\Models\Client;
use App\Models\Method;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class TestController extends Controller
{
    public function index(){
        return inertia('Modules/Test/Index', [
            'categories' => Method::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? config('app.perpage'))
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'active_on' => $user->created_at->format('d M Y'),
                ]),
            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('test.index')
        ]);

    }


    public function create(Request $request){
        Method::create(
            Request::validate([
                'name' => 'required|max:50',
            ])
        );
        return Redirect::route('test.index');

    }

    public function edit($id){
        return Method::findOrFail($id);
    }

    public function update(Request $request, $id){

        Method::findOrFail($id)->update(Request::validate(['name' => 'required']));
        return Redirect::route('test.index');
    }


    public function testEmail(){
//        Mail::send(new QuotationMail($customer));
        return "mail send successfully done";
    }
}
