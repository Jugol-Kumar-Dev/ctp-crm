<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Mail\QuotationMail;
use App\Models\Client;
use App\Models\CustomInvoice;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Method;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Transaction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use FontLib\Table\Type\kern;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Mockery\Generator\StringManipulation\Pass\RemoveUnserializeForInternalSerializableClassesPass;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;
use function Symfony\Component\Mime\toString;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {


        if (!auth()->user()->hasRole('Administrator')){
            $show =  auth()->user()->hasRole('Administrator')  || !auth()->user()->can('invoice.index');
            $my =  auth()->user()->hasRole('Administrator')  || !auth()->user()->can('invoice.ownonly');
            if($show && $my){
                abort(401);
            }
        }

        return inertia('Invoice/Index',[
            'invoices' => Invoice::query()
                ->with(['client', 'user', 'quotation', 'transactions'])
                ->latest()
                ->when(!Auth::user()->hasRole('Administrator') && auth()->user()->can('invoice.ownonly'), function($query){
                    $query->where('user_id', Auth::id());
                })
                ->when(Request::input('search'), function ($query, $search) {
                    $query->whereHas('client', function ($client) use($search){
                            $client
                                ->where('name',    'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($user) use($search){
                            $user
                                ->where('name',    'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('quotation', function ($user) use($search){
                            $user->where('subject', 'like', "%{$search}%");
                        })
                    ;
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($invoice) => [
                    'id' => $invoice->id,
                    'invoice_id' => $invoice->invoice_id,
                    'quotation_id' => $invoice->quotation_id,
                    'client' => $invoice->client ?? $invoice->quotation?->client,
                    'user' => $invoice->user,
                    'total_amount' => $invoice->total_price,
                    'discount' => $invoice->discount,
                    'grand_total' => $invoice->grand_total,
                    'pay' => $invoice->pay,
                    'due' => $invoice->due,
                    'invoice_type' => $invoice->invoice_type,
                    'created_at' => $invoice->created_at->format('d M Y'),
                    'edit_url' => URL::route('invoices.edit', $invoice->id),
                    'show_url' => URL::route('invoices.show', $invoice->id),
                    'invoice_url' => URL::route('invoices.downloadInvoice', $invoice->id),
                ]),
            'filters' => Request::only(['search','perPage']),
            'main_url' => URL::route('invoices.index'),
        ]);

    }

    public function create()
    {

        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('invoice.create')){
            abort(401);
        }

          if(auth()->user()->can('invoice.ownonly')){
              $clients = Client::query()
                  ->with(['users'])
                  ->where(function ($query) {
                      $query->where('is_client', true)
                          ->where('created_by', Auth::id());
                  })
                  ->orWhereHas('users', function ($query) {
                      $query->where('user_id', Auth::id());
                  })->where('is_client', true)
                  ->latest()->get();
          }else{
              $clients = Client::query()->where('is_client', true)
                  ->latest()->get();
          }




        return Inertia::render('Invoice/Create', [
            "quotations" => Quotation::all(),
            "clients"   => $clients,
            "paymentMethods" => Method::all(),
            "store_url" => URL::route('invoices.store')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('invoice.create')){
            abort(401);
        }
        Request::validate([
            'clientId' => 'required',
            'date' => 'required',
        ],[
            'clientId.required' => 'First Select An Client...',
            'qutDate.required' => 'Please Select Quotation Date...',
        ]);

//        return dd(Request::all());

        Invoice::create([
            'invoice_id' => now()->format('Ymd'),
            'client_id' => Request::input('clientId'),
            'invoice_date' => Request::input('date'),
            'user_id' => Auth::id(),
            'invoice_type' => 'custom',
            'items' => json_encode(Request::input('items')),
            'total_price' => Request::input('totalPrice'),
            'discount' => Request::input('discount') ?? 0,
            'grand_total' => Request::input('totalPrice'),
            'due' => Request::input('totalPrice'),
            'note' => Request::input('note'),
            'currency' => Request::input('currency'),
            'payment_policy' => Request::input('attachPaymentPolicy') ? Request::input('paymentPolicy') : NULL,
            'trams_of_service' => Request::input('attachServicePolicy') ? Request::input('servicePolicy') : NULL,
            'payment_methods' => Request::input('attachPaymentMethods') ? Request::input('payemtnPolicy') : NULL,
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Inertia\Response
     */



    public function invoiceItemsGenerate($invoice){


        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('invoice.show')){
            abort(401);
        }

        $pref = [];
        if (!is_null($invoice) && !is_null($invoice->quotation_id)){
            foreach (json_decode($invoice->quotation?->items) as $item){
                if ($item->checkPackages){
                    foreach ($item->checkPackages as $package){
                        $pref[] =[
                            'name'=> $package->descriptions,
                            'qty' => $package->qty,
                            'price' => $package->price,
                        ];
                    }
                }
                if ($item->checkFeatrueds){
                    foreach ($item->checkFeatrueds as $feared){
                        $pref[] =[
                            'name'=> $feared->name,
                            'qty' => $feared->qty,
                            'price' => $feared->price,
                        ];
                    }
                }
                if ($item->customItem){
//                foreach ($item->customItem as $cItem){
                    $pref[] =[
                        'name'=> $item?->customItem->description ?? 'custom_service',
                        'qty' => $item?->customItem->qty,
                        'price' => $item?->customItem->price,
                    ];
//                }
                }
            }
        }else{
            if (json_decode($invoice?->items)){
                foreach(json_decode($invoice->items) as $item){
                    $pref[] =[
                        'name'=> $item->description,
                        'qty' => 1,
                        'price' => $item->price,
                    ];
                }
            }
        }

        return $pref;
    }

    public function show(Invoice $invoice)
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('invoice.show')){
            abort(401);
        }

        if(auth()->user()->can('invoice.ownonly')){
            if($invoice->user_id != Auth::id()){
                abort(401);
            }
        }


        $invoice = $invoice->load('user', 'client', 'quotation', 'transactions', 'transactions.receivedBy:id,name', 'transactions.method:id,name');
        $pref = $this->invoiceItemsGenerate($invoice);


//        return $invoice->quotation->items;//->quotation->customItem;

        return Inertia::render('Invoice/Show',   [
            "invoice" =>$invoice,
            "pref" => $pref,
            "paymentMethods" => Method::all(),
            $downloadInvoiceUrl =
            "url" =>[
                "edit_url" => URL::route('invoices.edit', $invoice->id),
                "add_discount" => URL::route('invoices.addDiscount', $invoice->id),
                "invoice_url" => URL::route('invoices.downloadInvoice', $invoice->id),
                "payment_url" => URL::route('transaction.store')
            ]
        ]);
    }

    public function addDiscount($id){


        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('invoice.edit')){
            abort(401);
        }



        $invoice = Invoice::findOrFail($id);
        $invoice->discount = $invoice->discount + (int)Request::input('discount');
        $invoice->grand_total = $invoice->total_price - $invoice->discount;
        $invoice->due = $invoice->due - (int)Request::input('discount');
        $invoice->save();
        return back();
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\RedirectResponse
     */


    public function createInvoice($id)
    {

        if(auth()->user()->hasRole('Administrator')  || auth()->user()->can('invoice.edit') || auth()->user()->can('quotation.show')) {
            if (Request::input("pay") != null) {
                Request::validate([
                    'payment_method' => 'required'
                ]);
            }
            $quotation = Quotation::findOrFail(Request::input('quotationId'));
            $discount = $quotation->discount + Request::input('discount') ?? 0;
            $grandTotal = $quotation->total_price - $discount;
            $due = $grandTotal - (int)Request::input('pay');

            $invoice = Invoice::create([
                'invoice_id' => now()->format('Ymd'),
                'quotation_id' => $quotation->id,
                'client_id' => Request::input('clientId'),
                'user_id' => Auth::id(),
                'invoice_type' => 'quotation',
                'total_price' => $quotation->total_price,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                'pay' => Request::input('pay'),
                'due' => $due,
                'currency' => $quotation->currency ?? 'Taka',
                'invoice_date' => now(),
                'note' => Request::input('note'),
                'payment_policy' => $quotation?->payment_policy ?? NULL,
                'trams_of_service' => $quotation?->trams_of_service ?? NULL,
                'payment_methods' => $quotation?->payment_methods ?? NULL,
            ]);

            Transaction::create([
                'transaction_id' => now()->format('Ymd'),
                'transactionable_id' => $invoice->id,
                'transactionable_type' => "App\\Models\\Invoice",
                "purpose" => "#" . env('INV_PREFIX') . "_" . $invoice->invoice_id ?? NULL,
                'received_by' => Auth::id(),
                'payment_by' => Request::input('clientId'),
                "transaction_type" => "Credited",
                "amount" => $quotation->total_price,
                "pay" => Request::input('pay'),
                "due" => $due,
                "payment_date" => now(),
                "method_id" => Request::input('payment_method')
            ]);

            $data = $this->downloadInvoice($invoice->id, true);
            $clientEmail = $invoice->quotation?->client?->email ?? $invoice->client?->email;

            if (Request::input('sendEmail') || Request::input('sendEmail') == true || Request::input('sendEmail') == 'true') {
                if (is_array($data) && $clientEmail) {
                    Mail::to($clientEmail)->send(new InvoiceMail($data['invoice'], $data['pref']));
                }
            }
            return back();
        }else{
            abort(401);
        }

    }

    public function downloadInvoice($id, $emailData=false){
        $invoice = Invoice::with(['user', 'quotation', 'client', 'transactions'])->findOrFail($id);
        $pref = [];

        if (!is_null($invoice) && !is_null($invoice->quotation_id)){
            foreach (json_decode($invoice->quotation?->items) as $item){
                if ($item->checkPackages){
                    foreach ($item->checkPackages as $package){
                        $pref[] =[
                            'name'=> $package->descriptions,
                            'qty' => $package->qty,
                            'price' => $package->price,
                        ];
                    }
                }
                if ($item->checkFeatrueds){
                    foreach ($item->checkFeatrueds as $feared){
                        $pref[] =[
                            'name'=> $feared->name,
                            'qty' => $feared->qty,
                            'price' => $feared->price,
                        ];
                    }
                }
                if ($item->customItem){
//                foreach ($item->customItem as $cItem){
                    $pref[] =[
                        'name'=> $item?->customItem->description ?? 'custom_service',
                        'qty' => $item?->customItem->qty,
                        'price' => $item?->customItem->price,
                    ];
//                }
                }
            }
        }else{
            foreach(json_decode($invoice->items) as $item){
                $pref[] =[
                    'name'=> $item->description,
                    'qty' => 1,
                    'price' => $item->price,
                ];
            }
        }

        $clientName = $invoice->quotation?->client?->name ?? $invoice->client?->name;
        $isPrint = false;

        if($emailData){
            return [
                'invoice' => $invoice,
                'pref' => $pref,
            ];
        }

        $pdf = Pdf::loadView('invoice.quotationInvoice', compact('invoice','pref', 'isPrint'));
//        return view('invoice.quotationInvoice', compact('invoice','pref', 'isPrint'));
        return $pdf->download($clientName."_".now()->format('d_m_Y')."_".'invoice.pdf');
    }

    public function generateInvoicePDFFile($id){

        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('invoice.edit')){
            abort(401);
        }




        $invoice = CustomInvoice::findOrFail($id);

        $transactions = [];
        foreach($invoice->transactions as $item){
            $transactions[] = [
                "amount"     => $item->amount ?? 0,
                "user"       => $item->user,
                "method"     => $item->method->name,

                "pay_amount" => $item->pay_amount ?? 0,
                "discount"   => $item->discount ?? 0,
                "total_due"  => $item->total_due ?? 0,
                "old_total_pay" => $item->old_total_pay ?? 0,
                "date"       => $item->date->format('d M,y'),
                "note"       => $item->note,
            ];
        }

        $totalPay = $invoice->transactions->sum('pay_amount') + $invoice->transactions->sum('discount');

        $invoiceLastTransaction = $invoice->transactions->last() ?? [
                'pay_amount' => 0,
                'discount' => 0
            ];

        $data = [
            "invoice"       => $invoice,
            "client"        => $invoice->client,
            "invoice_item"  => $invoice->invoiceItems,
            'transactions'    => $transactions,
            "total_pay"       => $totalPay,
            "last_payment"    => $invoiceLastTransaction,
            'invoice_id' =>$invoice->created_at->format('Ymd').$invoice->id,
            'creator' => $invoice->user,
            "created" => $invoice->created_at->format('D, d F, Y'),
            'download_url' => URL::route('invoices.generateInvoicePDFFile', $invoice->id),
        ];


        return view('invoice.invoice', compact("data"));

        $pdf = Pdf::loadView('invoice.invoice', compact("data"));
        return $pdf->download('invoice.pdf');
    }


    public function edit($id){

        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('invoice.edit')){
            abort(401);
        }

        $invoice = Invoice::with('client')->findOrFail($id);


        if(auth()->user()->can('invoice.ownonly')){
            $clients = Client::query()
                ->with(['users'])
                ->where(function ($query) {
                    $query->where('is_client', true)
                        ->where('created_by', Auth::id());
                })
                ->orWhereHas('users', function ($query) {
                    $query->where('user_id', Auth::id());
                })->where('is_client', true)
                ->latest()->get();
        }else{
            $clients = Client::query()->where('is_client', true)
                ->latest()->get();
        }


        return Inertia::render('Invoice/Edit', [
            "clients"   => $clients,
            "invoice"       => $invoice,
            "update_url"    => URL::route('updateInvoices', $invoice->id),
        ]);
    }


    public function updateInvoice(Request $request, $id){


        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('invoice.edit')){
            abort(401);
        }

        $invoice = Invoice::findOrFail($id);

        Request::validate([
            'clientId' => 'required',
            'date' => 'required',
        ],[
            'clientId.required' => 'First Select An Client...',
            'qutDate.required' => 'Please Select Quotation Date...',
        ]);


        $invoice->update([
            'invoice_id' => now()->format('Ymd'),
            'client_id' => Request::input('clientId')['id'] ?? Request::input("clientId"),
            'user_id' => Auth::id(),
            'invoice_type' => 'custom',
            'items' => json_encode(Request::input('items')),
            'total_price' => Request::input('totalPrice'),
            'discount' => Request::input('discount') ?? 0,
            'grand_total' => Request::input('totalPrice'),
            'due' => Request::input('totalPrice') - $invoice->pay,
            'note' => Request::input('note'),
            'payment_policy' => Request::input('attachPaymentPolicy') ? Request::input('paymentPolicy') : NULL,
            'trams_of_service' => Request::input('attachServicePolicy') ? Request::input('servicePolicy') : NULL,
            'payment_methods' => Request::input('attachPaymentMethods') ? Request::input('payemtnPolicy') : NULL,
        ]);

        return Redirect::route('invoices.index');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('invoice.edit')){
            abort(401);
        }

        dd($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('invoice.delete')){
            abort(401);
        }

        $invoice = Invoice::findOrFail($id);
        if ($invoice->transactions){
            $invoice->transactions()->delete();
        }
        if ($invoice->project){
            $invoice->project()->delete();
        }
        $invoice->delete();
        return back();
    }


    public function addPayment(){

        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('invoice.edit')){
            abort(401);
        }

        $invoice = CustomInvoice::with(['client'])->findOrFail(Request::input('invoice_id'));

        $totalPay = Request::input('pay_amount')+Request::input('discount');

        Transaction::create([
            'u_id'       => date('Yd', strtotime(now())),
            'transaction_model' => 'App\\Models\\CustomInvoice',
            'transaction_model_id' => $invoice->id,
            'method_id'  => Request::input('payment_id'),
            'user_id'    => Auth::id(),
            'client_id'  => $invoice->client->id,
            'invoice_id' => $invoice->id,
            "purpose" => "#".env('INV_PREFIX')."_".$invoice->invoice_id ?? NULL,
            'amount'     => $invoice->grand_total,
            'pay_amount' => Request::input('pay_amount'),
            'discount'   => Request::input('discount'),

            'total_pay'  => $totalPay,
            'total_due'  => $invoice->due - $totalPay,

            'date'       => now(),
            'note'       => Request::input('payment_note'),

            'type'       => 'in'
        ]);

        $tk = $invoice->due - $totalPay;
        $invoice->update([
            'pay' =>  $invoice->pay + $totalPay,
            'due' => $tk
        ]);


        return back();
    }

    public function sendMail($id=null){

        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('invoice.edit')){
            abort(401);
        }

        Request::validate([
            'email' => 'required|email'
        ]);
        $email = Request::input('email');
        $data = $this->downloadInvoice($id, true);
        if($data && $email){
            Mail::to($email)->send(new InvoiceMail($data['invoice'], $data['pref']));
            return redirect()->back()->with([
                'message' => 'Email Send Success...'
            ]);
        }

        return redirect()->back()->withErrors([
            'message' => 'No have any email for send this data..'
        ]);

    }


}
