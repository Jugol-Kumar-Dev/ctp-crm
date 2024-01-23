<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CustomInvoice;
use App\Models\Design;
use App\Models\Domain;
use App\Models\Expanse;
use App\Models\Hosting;
use App\Models\Invoice;
use App\Models\Note;
use App\Models\Platform;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\Todo;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Website;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
/*    public function __invoke(Request $request)
    {
        if (!auth()->user()->can('dashboard.show')){
            abort(401);
        }



/*
        // custom invoice total incomes
        $invoiceTotalSeals = CustomInvoice::sum('total_price');
        $invoiceTotalDiscount = CustomInvoice::sum('discount');
        $cinvoiceGrandTotal = $invoiceTotalSeals - $invoiceTotalDiscount;

        $cinvoiceTotalPay = CustomInvoice::sum('pay');
        $cinvoiceTotalDue = CustomInvoice::sum('due');

        // quotation to invoice total incomes
        $invoiceSubTotal = Invoice::sum('sub_total');
        $invoiceGrandTotal = Invoice::sum('grand_total');
        $invoiceDiscount = Invoice::sum('discount');

        $invoiceTotalPay = Invoice::sum('pay');
        $invoiceTotalDue = Invoice::sum('due');


        // total expanse
        $totalExpanse = Expanse::sum('amount');


        // calculation
        $totalSeals = $invoiceTotalSeals + $invoiceSubTotal;
        $totalDiscount = $invoiceTotalDiscount + $invoiceDiscount;

        $totalGrand = $invoiceGrandTotal + $cinvoiceGrandTotal;
        $totalProfit = $totalGrand - $totalExpanse;

        $grandTotalPay = $cinvoiceTotalPay + $invoiceTotalPay;
        $grandTotalDue = $cinvoiceTotalDue + $invoiceTotalDue;


//        return $totalSeals. " ".$totalDiscount;

        $invoiceTotalPay = Invoice::sum('pay');
        $invoiceTotalDue = Invoice::sum('due');


        $todayIn = Transaction::whereDate('created_at', Carbon::now()->format('Y-m-d'))->where('type', 'in')->sum('total_pay');
        $todayExp = Transaction::whereDate('created_at',  Carbon::now()->format('Y-m-d'))->where('type', 'out')->sum('total_pay');

        $invoiceTotalSeals = CustomInvoice::sum('total_price');
        $invoiceTotalDiscount = CustomInvoice::sum('discount');

        $todayQseals = Quotation::whereDate('created_at', date('Y-m-d'))->sum('price');
        $todayQdiscount = Quotation::whereDate('created_at', date('Y-m-d'))->sum('discount');

        $todayIseals = CustomInvoice::whereDate('created_at', date('Y-m-d'))->sum('total_price');
        $todayIdiscount = CustomInvoice::whereDate('created_at', date('Y-m-d'))->sum('discount');

        $todaySeals = $todayQseals + $todayIseals;
        $todayDiscount = $todayQdiscount + $todayIdiscount;


//        $quot = Quotation::withSum('transactions', 'amount')->get();

//        return $quot;




//        $quotation = Quotation::with('transactions')->get();

        $months = range(0, 11);


        // This Year Transaction Chart Query
//        $tranByCount = Transaction::query()
//            ->whereYear('created_at', date('Y'))
//            ->selectRaw("month(created_at) as month")
//            ->selectRaw('count(*) as count')
//            ->selectRaw('Sum(total_pay) as amount')
//            ->groupBy('month')
//            ->orderBy('month')
////            ->get();
//            ->pluck('count','month')
//            ->values()
//            ->toArray();




//        $tranByAmount = Transaction::query()
//            ->whereYear('created_at', date('Y'))
//            ->selectRaw("month(created_at) as month")
//            ->selectRaw('count(*) as count')
//            ->selectRaw('Sum(total_pay) as amount')
//            ->groupBy('month')
//            ->orderBy('month')
//            ->pluck('amount', 'month')
//            ->values()
////                ->get()
//            ->toArray();



        $transactions = Transaction::whereYear('created_at', '=', date('Y'))
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount) as total'))
            ->selectRaw(DB::raw('count(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $tranByCount = array_values(array_fill_keys($months, 0));
        foreach ($transactions as $transaction) {
            $tranByCount[$transaction->month - 1] = $transaction->count;
        }


        // this year transaction amount chart query
        $transactions = Transaction::whereYear('created_at', '=', date('Y'))
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total_pay) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

//        return $transactions;

        $tranByAmount = array_values(array_fill_keys($months, 0));
        foreach ($transactions as $transaction) {
            $tranByAmount[$transaction->month - 1] = $transaction->total;
        }

//        return $tranByAmount;


        // This Year Expanse Chart Query
        $expanses = Expanse::whereYear('created_at', '=', date('Y'))
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $tranByExpanse = array_fill_keys($months, 0);
        foreach ($expanses as $exp) {
            $tranByExpanse[$exp->month - 1] = $exp->total;
        }

*/



//        return Inertia::render('Test');

    /*    [
            "trans" => [
                'clients' => Client::count(),
                'packages' => Design::count(),
                'domain' => Domain::count(),
                'hosting' => Hosting::count(),
                'cInvoice' => CustomInvoice::count(),
                'platforms' => Platform::count(),
                'projects' => Project::count(),
                'quotation' => Quotation::count(),
                'users' => User::count(),
                'website' => Website::count(),
                'work' => Work::count(),


                // transaction list
                'totalSeals' => $totalSeals,
                'totalDiscount' => $totalDiscount,
                'totalGrand' => $totalGrand,
                'totalExpanse' => $totalExpanse,
                'totalProfit' => $totalProfit,

                'totalPay' => $grandTotalPay,
                'totalDue' => $grandTotalDue,




                'todayIn' => $todayIn,
                'todayExp' => $todayExp,

                'todaySeals' => $todaySeals,
                'todayDiscount' => $todayDiscount,

                'tranByCount' => $tranByCount,
                'tranByAmount' => $tranByAmount,
                'tranByExp' => $tranByExpanse,
            ]
        ]*/

//    }
//
    public function __invoke(Request $request){


        if (!auth()->user()->can('dashboard.show') || auth()->user()->hasRole('administrator')){
            abort(401);
        }



        if (auth()->user()->hasRole('administrator')) {
            $notes = Note::with(['users','noteCategory'])->get();
        } else {
            $notes = Note::with(['users','noteCategory'])->where(function($query) {
                $query->whereHas('users', function($q) {
                    $q->where('user_id', Auth::id());
                });
            })->get();
        }


//        return dd(auth()->user()->hasRole('administrator'));


        $clients = Client::query()
            ->with(['createdBy'])
            ->whereNotNull('follow_up')
            ->whereDate('follow_up',Carbon::today())
            ->where('is_client', true)
            ->get();

        $othersFollowUpClients = Client::query()
            ->with(['createdBy'])
            ->whereNotNull('follow_up')
            ->whereDate('follow_up', "!=", Carbon::today())
            ->where('is_client', true)
            ->when(!auth()->user()->hasRole('Administrator'), function ($query){
                $query->where('updated_by', Auth::id());
            })
            ->get();


        $leads = Client::query()
            ->with(['createdBy'])
            ->whereNotNull('follow_up')
            ->whereDate('follow_up', Carbon::today())
            ->where('is_client', false)
            ->get();

        $othersFollowUpLeads = Client::query()
            ->with(['createdBy'])
            ->whereNotNull('follow_up')
            ->whereDate('follow_up', "!=", Carbon::today())
            ->where('is_client', false)
            ->when(!auth()->user()->hasRole('Administrator'), function ($query){
                $query->where('updated_by', Auth::id());
            })
            ->get();



        $todos = Todo::query()
            ->with('user')
            ->when(!auth()->user()->hasRole('administrator'), function ($q){
                $q->where('user_id', Auth::id());
            })
            ->whereNull('todo_id')
            ->where('priority', '!=', 'Complete')
            ->latest()
            ->get();


        return Inertia::render('Test',[
            'followup_leads' => $leads,
            'others_followup_leads' => $othersFollowUpLeads,
            'followup_clients' => $clients,
            'others_followup_Clients' => $othersFollowUpClients,
            'notes' => $notes,
            'todos' => $todos
        ]);

    }


}
