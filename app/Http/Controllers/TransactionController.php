<?php

namespace App\Http\Controllers;

use App\Models\CustomInvoice;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Transaction;
use App\Models\TransactionLine;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dotenv\Repository\Adapter\ReaderInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {
        if (!auth()->user()->hasRole('Administrator')){
            if(!auth()->user()->can('transaction.index') && !auth()->user()->can('transaction.own')){
                abort(401);
            }
        }

//        if (!auth()->user()->can('transaction.index') || auth()->user()->hasRole('administrator')){
//            abort(401);
//        }


        $transactions = Transaction::query()
            ->latest()
            ->with(['receivedBy', 'paymentBy', 'method'])
            ->when(!Auth::user()->hasRole('Administrator') && auth()->user()->can('transaction.own'), function($query){
                $query->where('received_by', Auth::id());
            })
            ->when(Request::input('search'), function ($query, $search) {
                $query->where('id', 'like', "%{$search}%")
                ->orWherehas('receivedBy', function ($query)use($search){
                    $query->where('name',    'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWherehas('method', function ($query)use($search){
                    $query->where('name',    'like', "%{$search}%");
                });
            })
            ->when(Request::input('byStatus'), function ($query, $search){
                $query->where('transaction_type', $search);
            })
            ->when(Request::input('dateRange'), function ($query, $search){
                $startDateTime = Carbon::parse($search[0])->startOfDay();
                $endDateTime = Carbon::parse($search[1])->endOfDay();
                $query->whereBetween('payment_date', [$startDateTime, $endDateTime]);
            })
            ->when(Request::input('employee'), function ($query, $search){
                $query->whereHas('receivedBy', function ($query)use($search){
                    $query->where('id', (int)$search);
                });
            });







        if(!Auth::user()->hasRole('Administrator') && auth()->user()->can('transaction.own')){

            $creditObj =  clone $transactions;
            $credited = $creditObj->where('transaction_type', 'Credited')->where('received_by', Auth::id())->sum('pay');
            $debidedObj = clone $transactions;
            $debided = $debidedObj->where('transaction_type', 'Debited')->where('received_by', Auth::id())->sum('pay');

//            $credited = Transaction::where('transaction_type', 'Credited')->where('received_by', Auth::id())->sum('pay');
//            $debided = Transaction::where('transaction_type', 'Debited')->where('received_by', Auth::id())->sum('pay');
        }else{
            $creditObj =  clone $transactions;
            $debided =$creditObj->where('transaction_type', 'Debited')->sum('pay');
            $debidedObj = clone $transactions;
            $credited = $debidedObj->where('transaction_type', 'Credited')->sum('pay');
        }


        $creditSum = clone $transactions;
        $lodedDatas = $transactions->latest()
            ->paginate(Request::input('perPage') ?? 10)
            ->withQueryString()
            ->through(fn($tra) => [
                'tran' => $tra,
                'model' => $tra->transaction_model && $tra->transaction_model_id ? $tra->transaction_model::find($tra->transaction_model_id) : null,
                'created_at' => $tra->created_at->format('d M Y'),
                'show_url' => URL::route('expense.show', $tra->id),
            ]);


        if (Request::input('export_pdf') === 'true'){
            return $this->loadDownload($lodedDatas, Request::input('dateRange'));
        }

        if (Request::input('exportPdf') === 'true'){
            return $this->loadDownload($lodedDatas, Request::input('dateRange'));
        }



        return inertia('Transaction/Index', [
            'transactions' => $lodedDatas,
            'filters'     => Request::only(['search','perPage', 'byStatus', 'dateRange', 'employee']),
            "main_url" => Url::route('transaction.index'),
            'employees' => User::query()->select('name', 'id')->get(),
            "credited" => $credited,
            "debited" => $debided
        ]);
    }

    protected function loadDownload($data, $dateRange=null){

        if (!auth()->user()->can('transaction.export') || auth()->user()->hasRole('administrator')){
            abort(401);
        }


        Pdf::setOption(['enable_php', true]);
//        return view('reports.pdf_transaction_list', compact('data', 'dateRange'));
        $pdf = Pdf::loadView('reports.pdf_transaction_list', compact('data', 'dateRange'));
        return $pdf->download("transaction"."_".now()->format('d_m_Y')."_".'transaction.pdf');
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Request::validate([
            'invoiceId' => 'required',
            'clientId' => 'required',
            'payment_method' => 'required',
            'date' => 'required'
        ]);


        $invoice = Invoice::findOrFail(Request::input('invoiceId'));

        $invoice->due = $invoice->due - (int)Request::input("pay");
        $pay = $invoice->pay + (int)Request::input("pay");
        $invoice->pay = $pay;
        $invoice->update();

        Transaction::create([
            'transaction_id' =>  now()->format('Ymd'),
            'transactionable_id' => Request::input('invoiceId'),
            'transactionable_type' => "App\\Models\\Invoice",
            "purpose" => "#".env('INV_PREFIX')."_".$invoice->invoice_id ?? NULL,
            'received_by' => Auth::id(),
            'payment_by' => Request::input('clientId'),
            "transaction_type" => "Credited",
            "amount" => Request::input("totalPrice"),
            "pay" => Request::input("pay"),
            "due" => $invoice->due,
            "payment_date" => Request::input('date'),
            "method_id" => Request::input('payment_method')
        ]);

        return back();
    }


    public function saveQuotationTransaction(Request $request){
        $quotation = Quotation::with('client')->findOrFail(Request::input('quotation_id'));
        $grandTotal = Request::input('grandTotal') ?? 0;
        $discount   = Request::input('discount') ?? 0;
        $payAmount  = Request::input('pay_amount') ?? 0;

//        return ["pay_amount" => $payAmount, "discount" => $discount, "grand total" => $grandTotal, "quotation" => $quotation, "transactions" => $quotation->transactions];


        TransactionLine::create([
            'u_id' => 'Transaction_'.rand(73862, 5632625),
            'user_id' => Auth::id(),
            'type' => 'in',
            'subject_model' => "App\\Models\\Quotation",
            'subject_id' => $quotation->id,
            'note' => Request::input('payment_note'),
            'amount' => $payAmount + $discount,
            'discount' => $discount,
            'method_id' =>Request::input('method_id'),
            'date' => now()
        ]);

        Transaction::create([
            'method_id'  => Request::input('method_id'),
            'user_id'    => Auth::id(),
            'client_id'  => $quotation->client->id,
            'quotation_id' => $quotation->id ?? Request::input('quotation_id'),

            'amount'     => $grandTotal,
            'total_pay'  => $payAmount + $discount,
            'old_total_pay' => Quotation::findOrFail(Request::input('quotation_id'))->transactions->sum('total_pay') + $payAmount + $discount,
            'pay_amount' => $payAmount,
            'discount'   => $discount,
            'total_due'  => ($grandTotal - $payAmount - $discount) ?? 0,


            'date'       => now(),
            'note'       => Request::input('payment_note')
        ]);
        return back();
    }


    public function getDueTransactions()
    {
        if (!auth()->user()->hasRole('Administrator')){
            if(!auth()->user()->can('duetrx.index') && !auth()->user()->can('duetrx.own')){
                abort(401);
            }
        }

        $transactions = Invoice::query()
            ->with(['client:id,name', 'user:id,name'])
            ->where('due', '>', 0)
            ->when(!Auth::user()->hasRole('Administrator') && auth()->user()->can('duetrx.own'), function($query){
                $query->where('user_id', Auth::id());
            })
            ->when(Request::input('search'), function ($query, $search) {
                $query->where('id', 'like', "%{$search}%")
                    ->orWherehas('user', function ($query)use($search){
                        $query->where('name',    'like', "%{$search}%");
                    })
                    ->orWherehas('client', function ($query)use($search){
                        $query->where('name',    'like', "%{$search}%");
                    });
            })
            ->when(Request::input('employee'), function ($query, $search){
                $query->whereHas('user', function ($query)use($search){
                    $query->where('id', (int)$search);
                });
            })
            ->when(Request::input('dateRange'), function ($query, $search){
                $startDateTime = Carbon::parse($search[0])->startOfDay();
                $endDateTime = Carbon::parse($search[1])->endOfDay();
                $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
            })
            ->select('id', 'invoice_id', 'client_id', 'user_id', 'grand_total', 'pay', 'due', 'created_at')
            ->latest();



        $dueObj = clone $transactions;
        $dueTotal = $dueObj->where('due', '>', 0)->sum('due');
        $payObj = clone $transactions;
        $payTotal = $payObj->where('due', '>', 0)->sum('pay');
        $grandObj = clone $transactions;
        $grandTotal = $grandObj->where('due', '>', 0)->sum('grand_total');


        $passionated = clone $transactions;
        $allData = $passionated->paginate(Request::input('perPage') ?? 10)
            ->withQueryString();

        return Inertia::render('DueReport/Index',[
            'transactions' => $allData,
            'filters'     => Request::only(['search','perPage', 'byStatus', 'dateRange', 'employee']),
            'employees' => User::query()->select('name', 'id')->get(),
            "main_url" => Url::route('dueTransactions'),
            'dueTotal' => $dueTotal,
            'payTotal' => $payTotal,
            'grandTotal' => $grandTotal
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
