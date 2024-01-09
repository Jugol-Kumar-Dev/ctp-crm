<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class LeadController extends Controller
{
    public function index()
    {
        $names = array_column(Auth::user()->roles->toArray(), 'name');
        $admin = in_array('Administrator', $names);


        $clients = null;
        if (auth()->user()->can('leads.index') || $admin) {
            $clients = Client::query()->with(['projects', 'users', 'createdBy', 'updatedBy'])
                ->latest()
                ->where('is_client', false)
                ->Where('status', '!=', 'Converted to Customer')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('email', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('secondary_phone', 'like', "%{$search}%");
                })
                ->when(Request::input('byStatus'), function ($query, $search) {
                    $query->where('status', $search);
                })
                ->when(Request::input('dateRange'), function ($query, $search) {
                    $startDateTime = Carbon::parse($search[0])->startOfDay();
                    $endDateTime = Carbon::parse($search[1])->endOfDay();
                    $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
                });
        } elseif (auth()->user()->can('leads.ownonly')) {
            $cliests = Client::query()
                ->with(['users'])
                ->where('is_client', false)
                ->where('status', '!=',  "Converted to Customer")
                ->where('created_by', Auth::id());
//                ->orWhereHas('users', function($query){
//                    $query->where('users.id', Auth::id());
//                });

            if(Request::input('search')){
                $search = Request::input('search');
                $clients = $cliests->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            }elseif(Request::input('byStatus')){
                $search = Request::input('byStatus');
                $clients = $cliests->where('status', $search);
            }elseif(Request::input('dateRange')){
                $search = Request::input('dateRange');
                $startDateTime = Carbon::parse($search[0])->startOfDay();
                $endDateTime = Carbon::parse($search[1])->endOfDay();
                $clients = $cliests->whereBetween('created_at', [$startDateTime, $endDateTime]);
            }else{
                $clients = $cliests->orWhereHas('users', function($query){
                    $query->where('user_id', Auth::id());
                });
            }
        } else {
            abort(404);
        }

            $clients = $clients->latest()
            ->paginate(Request::input('perPage') ?? 10)
            ->withQueryString()
            ->through(fn($client) => [
                'id' => $client->id,
                'name' => $client->name,
                'phone' => $client->phone,
                'email' => $client->email,
                'assigned' => $client->users,
                'createdBy' => $client->createdBy,
                'updatedBy' => $client->updatedBy,
                'photo' => '/images/avatar.png',
                'status' => $client->status,
                'followUp' => $client->follow_up,
                'followUpMessage' => $client->follow_up_message,
                'created_at' => $client?->created_at?->format('d M Y'),
                'show_url' => URL::route('leads.show', $client->id)
            ]);



        if (Request::input('export_pdf') === 'true') {
            return $this->loadDownload($clients);
        }

        return inertia('Modules/Leads/Index', [
            'clients' => $clients,
            'users' => User::all(),
            'filters' => Request::only(['search', 'perPage', 'byStatus', 'dateRange']),
            "main_url" => Url::route('leads.index'),
        ]);

    }

    protected function loadMyLeads()
    {
        $cliests = Client::query()
            ->with('users')
            ->where('is_client', false)
            ->where('status', '!=',  "Converted to Customer")
            ->where('created_by', Auth::id())
            ->orWhereHas('users', function($query){
                $query->where('user_id', Auth::id());
            })
            ->when(Request::input('search'), function ($query, $search){
                $query
                    ->where('created_by', Auth::id())
                    ->orWhereHas('users', function($query){
                        $query->where('user_id', Auth::id());
                    })
                    ->where('email', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->when(Request::input('dateRange'), function ($query, $search) {
                $startDateTime = Carbon::parse($search[0])->startOfDay();
                $endDateTime = Carbon::parse($search[1])->endOfDay();
                $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
            })
            ->when(Request::input('byStatus'), function ($query, $search) {
                $query->where('status', $search);
            })
            ->when(Request::input('dateRange'), function ($query, $search) {
                $startDateTime = Carbon::parse($search[0])->startOfDay();
                $endDateTime = Carbon::parse($search[1])->endOfDay();
                $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
            })
            ->latest()
            ->paginate(Request::input('perPage') ?? 10)
            ->withQueryString()
            ->through(fn($client) => [
                'id' => $client->id,
                'name' => $client->name,
                'phone' => $client->phone,
                'email' => $client->email,
                'assigned' => $client->users,
                'createdBy' => $client->createdBy,
                'updatedBy' => $client->updatedBy,
                'photo' => '/images/avatar.png',
                'status' => $client->status,
                'followUp' => $client->follow_up,
                'created_at' => $client?->created_at?->format('d M Y'),
                'show_url' => URL::route('leads.show', $client->id)
            ]);


        return inertia('Modules/Leads/Index', [
            'clients' => $cliests,
            'users' => User::all(),
            'filters' => Request::only(['search', 'perPage', 'byStatus', 'dateRange']),
            "main_url" => Url::route('leads.index'),
        ]);

    }

    private function readALlLeads()
    {

        $clients = Client::query()->with(['projects', 'users', 'createdBy', 'updatedBy'])
            ->latest()
            ->where('is_client', false)
            ->Where('status', '!=', 'Converted to Customer')
            ->when(Request::input('search'), function ($query, $search) {
                $query
                    ->where('email', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('secondary_phone', 'like', "%{$search}%");
            })
            ->when(Request::input('byStatus'), function ($query, $search) {
                $query->where('status', $search);
            })
            ->when(Request::input('dateRange'), function ($query, $search) {
                $startDateTime = Carbon::parse($search[0])->startOfDay();
                $endDateTime = Carbon::parse($search[1])->endOfDay();
                $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
            })
            ->latest()
            ->paginate(Request::input('perPage') ?? 10)
            ->withQueryString()
            ->through(fn($client) => [
                'id' => $client->id,
                'name' => $client->name,
                'phone' => $client->phone,
                'email' => $client->email,
                'assigned' => $client->users,
                'createdBy' => $client->createdBy,
                'updatedBy' => $client->updatedBy,
                'photo' => '/images/avatar.png',
                'status' => $client->status,
                'followUp' => $client->follow_up,
                'created_at' => $client?->created_at?->format('d M Y'),
                'show_url' => URL::route('leads.show', $client->id)
            ]);

    }

    protected function loadDownload($data)
    {
//        if (!auth()->user()->can('leads.download')){
//            abort(404);
//        }
        Pdf::setOption(['enable_php', true]);
//        return view('reports.pdf_lead_list', compact('data'));
        $pdf = Pdf::loadView('reports.pdf_lead_list', compact('data'));
        return $pdf->download("Lead_Sheet" . "_" . now()->format('d_m_Y') . "_" . 'quotation.pdf');
    }

    public function show($id)
    {
//        if (!auth()->user()->can('leads.show')){
//            abort(404);
//        }
        $client = Client::with('users')->findOrFail($id);

        return $client;
    }


}
