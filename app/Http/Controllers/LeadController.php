<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class LeadController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $admin = $user->hasRole('Administrator');
        $ownOnly = $user->can('leads.ownonly');

        if (!$admin) {
            if (!auth()->user()->can('leads.index') && !$ownOnly) {
                abort(401, 'Your Not Autorized For Access Tthis Page');
            }
        }


        $clients = Client::query()
            ->select('id', 'name', 'phone', 'email', 'created_by', 'updated_by', 'status', 'created_at')
            ->with(['users:id,name', 'createdBy:id,name', 'updatedBy:id,name'])
            ->where('is_client', false)
            ->Where('status', '!=', 'Converted to Customer')
            ->when(!$admin && $ownOnly, function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->where('created_by', $user->id)
                        ->orWhereHas('users', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        });
                });
            })
            ->when(Request::input('search'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('email', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('secondary_phone', 'like', "%{$search}%");
                });
            })
            ->when(Request::input('byStatus'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->when(Request::input('dateRange'), function ($query, $dateRange) {
                $startDateTime = Carbon::parse($dateRange[0])->startOfDay();
                $endDateTime = Carbon::parse($dateRange[1])->endOfDay();
                $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
            })
            ->when(Request::input('employee'), function ($query, $search) {
                $query->where(function ($query)use($search) {
                    $query->where('created_by', $search)
                        ->orWhereHas('users', function ($query) use ($search) {
                            $query->where('user_id', $search);
                        });
                });
            })
            ->latest()
            ->paginate(Request::input('perPage') ?? config('app.perpage'));


        return inertia('Leads/Index', [
            'clients' => $clients,
            'users' => User::query()->select('id', 'name')->get(),
            'filters' => Request::only(['search', 'perPage', 'byStatus', 'dateRange','employee']),
            "main_url" => Url::route('leads.index'),
        ]);

    }


    /*
     * old index method download from cpanel.
     * this method problem is not working for unique user
     * this method not filtering unique user leads
     * */


    public function oldIndex()
    {


        $show = auth()->user()->hasRole('administrator') || !auth()->user()->can('leads.index');
        $my = auth()->user()->hasRole('administrator') || !auth()->user()->can('leads.ownonly');

        if ($show && $my) {
            abort(401);
        }

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
            $clients = Client::query()
                ->with(['users'])
                ->where(function ($query) {
                    $query->where('is_client', false)
                        ->where('created_by', Auth::id());
                })
                ->orWhereHas('users', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->when(Request::input('dateRange'), function ($query, $search) {
                    $startDateTime = Carbon::parse($search[0])->startOfDay();
                    $endDateTime = Carbon::parse($search[1])->endOfDay();
                    $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
                })
                ->when(Request::input('byStatus'), function ($query, $search) {
                    $query->where('status', $search);
                });
            if (Request::input('search')) {
                $search = Request::input('search');
                $clients = $clients->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })->where('is_client', false);
            } else {
                $clients = $clients->where('is_client', false);
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

    protected function loadDownload($data)
    {

        Pdf::setOption(['enable_php', true]);
        $pdf = Pdf::loadView('reports.pdf_lead_list', compact('data'));
        return $pdf->download("Lead_Sheet" . "_" . now()->format('d_m_Y') . "_" . 'quotation.pdf');
    }

    public function show($id)
    {
        if (auth()->user()->hasRole('administrator') || !auth()->user()->can('leads.show')) {
            abort(401);
        }


        if (auth()->user()->hasRole('Administrator') || auth()->user()->can('leads.index')) {
            $user = Client::findOrFail($id)->load('users', 'transactions', 'transactions.receivedBy',
                'invoices', 'invoices.user',
                'transactions.method',
                'quotations', 'quotations.user', 'projects',
                'projects.users', 'createdBy', 'updatedBy');
        } else {
            $user = Client::query()
                ->with('users')
                ->where(function ($query) use ($id) {
                    $query->where('id', $id);
                })
                ->where(function ($query) {
                    $query->where('created_by', Auth::id())
                        ->orWhereHas('users', function ($query) {
                            $query->where('users.id', Auth::id());
                        });
                })
                ->firstOrFail();
        }

        if (Request::input('edit')) {
            return $user;
        }


        return inertia('Leads/Show', [
            "user" => $user,
            'users' => User::all(),
            'image' => "./images/avatar.png",
            'show_url' => URL::route('clients.show', $user->id),
        ]);


    }

    public function destroy($id)
    {
        if (auth()->user()->hasRole('administrator') || !auth()->user()->can('leads.delete')) {
            abort(401);
        }

        $client = Client::findOrFail($id);

        $client->delete();
        return back();
    }

}
