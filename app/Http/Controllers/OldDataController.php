<?php

namespace App\Http\Controllers;

use App\Models\OldInvoice;
use App\Models\OldQuotation;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class OldDataController extends Controller
{

    public function quotation(){

        if (!auth()->user()->can('settings.old_data') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        return inertia('OldData/Quotation', [
            'oldQuotations' =>  OldQuotation::query()
                ->with(['user', 'client'])
                ->when(Request::input('search'), function ($query, $search) {
                    $query
                        ->where('subject', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('client', function ($client) use($search){
                            $client->where('name',    'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($user) use($search){
                            $user->where('name',    'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhere('additional', 'like', "%{$search}%")
                        ->orWhere('note', 'like', "%{$search}%")
                        ->orWhere('additional_price', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                })
                ->when(Request::input('dateRange'), function ($query, $search){
                    $start_date = date('Y-m-d H:i:s', strtotime($search[0]));
                    $end_date = date('Y-m-d H:i:s', strtotime($search[1]));
                    $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })
                ->oldest()
                ->paginate(Request::input('perPage') ?? config('app.perpage'))
                ->withQueryString()
                ->through(fn($quotation) => [
                    'quotation' => $quotation,
                ]),
            'filters'  => Request::only(['search','perPage', 'dateRange']),
            'main_url' => URL::route('oldQuotation')
        ]);
    }

    public function showQuotation($id){
        $data = OldQuotation::with(['user', 'client'])->findOrFail($id);
        return  inertia('OldData/ShowQuotation',[
            'content' => $data
        ]);
    }

    public function invoice(){

        if (!auth()->user()->can('settings.old_data') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        return inertia('OldData/Invoice', [
            'oldInvoice' =>  OldInvoice::query()
                ->with(['quotation', 'quotation.client'])

                ->when(Request::input('search'), function ($query, $search) {
                    $query->wherehas('quotation', function ($query)use($search){
                        $query->where('subject',    'like', "%{$search}%")
                            ->orWhere('additional', 'like', "%{$search}%")
                            ->orWhere('note', 'like', "%{$search}%")
                            ->orWhere('id', 'like', "%{$search}%");
                    })->orWherehas('quotation.client', function ($query)use($search){
                        $query->where('name',  'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                        ->orWhere('discount', 'like', "%{$search}%")
                        ->orWhere('grand_total', 'like', "%{$search}%")
                        ->orWhere('sub_total', 'like', "%{$search}%");
                })
                ->when(Request::input('dateRange'), function ($query, $search){
                    $start_date = date('Y-m-d H:i:s', strtotime($search[0]));
                    $end_date = date('Y-m-d H:i:s', strtotime($search[1]));
//                $query->whereBetween('created_at', [$start_date, $end_date]);
                    $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })
                ->oldest()
                ->paginate(Request::input('perPage') ?? config('app.perpage'))
                ->withQueryString()
                ->through(fn($invoice) => [
                    'invoice' => $invoice,
                ]),
            'filters'  => Request::only(['search','perPage', 'dateRange']),
            'main_url' => URL::route('oldInvoice')
        ]);
    }

    public function showInvoice($id){
        $data = OldInvoice::with(['quotation', 'quotation.client'])->findOrFail($id);
        return  inertia('OldData/ShowInvoice',[
            'content' => $data
        ]);
    }

}
