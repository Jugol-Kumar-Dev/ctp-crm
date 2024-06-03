<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {

        $data = Activity::query()
            ->with('causer')
            ->when(Request::input('search'), function ($query, $search) {
                $query->where('log_name', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when(Request::input('logName'), function ($query, $search) {
                $query->where('log_name', $search);
            })
            ->when(Request::input('filterEvents'), function ($query, $search) {
                $query->where('event', $search);
            })
            ->when(Request::input('dateRange'), function ($query, $search) {
                $startDateTime = Carbon::parse($search[0])->startOfDay();
                $endDateTime = Carbon::parse($search[1])->endOfDay();
                $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
            })
            ->when(Request::input('employee'), function ($query, $search){
                $query->whereHas('causer', function ($query)use($search){
                    $query->where('id', (int)$search);
                });
            });
        if(Request::input('isDataClear') == 'true'){
            $data->delete();
            return back();
        }

        $logs = $data->latest()
        ->paginate(Request::input('perPage') ?? 10)
        ->withQueryString();


        return inertia('ActivityLog', [
            'logs' => $logs,
            'action' => Activity::query()->select('event')->distinct()->get(),
            'log_names' => Activity::query()->select('log_name')->distinct()->get(),
            'users' => User::query()->select('id', 'name')->get(),
            'filters' => Request::only(['search', 'perPage', 'logName', 'dateRange', 'filterEvents', 'employee']),
            "main_url" => Url::route('log.index')
        ]);
    }
}
