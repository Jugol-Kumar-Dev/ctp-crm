<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\WorkSummery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class WorkingSummeryController extends Controller
{
    public function index()
    {
        return inertia('WorkSummery/Index', [
            'summery' => WorkSummery::query()->where('user_id', Auth::id())->latest()->paginate(10), //
            "main_url" => Url::route('leads.index'),
        ]);
    }

    public function adminIndex()
    {
        return inertia('WorkSummery/Admin', [
            'summery' => WorkSummery::query()->with('user')->latest()->when(Request::input('user'), function ($query, $search){
                $query->where('user_id', $search);
            })->paginate(10), //->where('user_id', Auth::id())
            'users' => User::all(),
            "main_url" => Url::route('leads.index'),
        ]);
    }

    public function store()
    {

        $exist = WorkSummery::query()->where('user_id', Auth::id())
            ->whereDate('submit_date', now()->toDateString())->first();


        if($exist){
            return back()->withErrors(['message' => "Today summery already submitted."]);
        }else{
            Request::validate([
                'summery' => 'required'
            ]);

            WorkSummery::create([
                'user_id' => Auth::id(),
                'message' => Request::input('summery'),
                'submit_date' => now()
            ]);

            return back();
        }


    }

    public function edit()
    {

    }

    public function destroy()
    {

    }
}
