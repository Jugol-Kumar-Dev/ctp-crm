<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CustomInvoice;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Method;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use FontLib\Table\Type\kern;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
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
        return inertia('Modules/Invoices/Index', [
            'invoices' => CustomInvoice::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('subject', 'like', "%{$search}%")
                        ->orWhereHas('client', function ($client) use($search){
                            $client->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($user) use($search){
                            $user->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('invoiceItems', function ($invoiceItem) use($search){
                            $invoiceItem->where('item_name', 'like', "%{$search}%");
                        })
                    ;
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($invoice) => [
                    'id' => $invoice->id,
                    'invoice' => $invoice,
                    'name' =>  $invoice->client->name,
                    'creator' => $invoice->user->name,
                    'created_at' => $invoice->created_at->format('d M Y'),
                    'invice_url' => URL::route('invoices.show', $invoice->id),
                    "edit_url" => URL::route('invoices.edit', $invoice->id),
                ]),
            'filters' => Request::only(['search','perPage']),
            'main_url' => URL::route('invoices.index'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Modules/Invoices/Create', [
            "clients"   => Client::all(['id','name']),
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
//        Request::validate([
//            'client_id' => "required",
//            'date' => "required",
//        ]);

        $quotation = CustomInvoice::create([
            'user_id'       => Auth::id(),
            'client_id'        => Request::input('client_id'),
            'subject'          => Request::input('subject'),
//            'date'             => Request::input('date')->format('d-y-m'),
            'status'           => filled(Request::input('status')),
            'trams_and_condition' => Request::input('trams_and_condition'),
            'privicy_and_policy'  => Request::input('payment_policy'),
        ]);


        foreach (Request::input('quatations') as $item){
            InvoiceItem::create([
                'invoice_id' => $quotation->id,
                'item_name'  => $item['itemname'],
                'price'      => $item['price'],
                'discount'   => $item['discount'],
            ]);
        }

        return redirect()->route('invoices.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Inertia\Response
     */
    public function show(CustomInvoice $invoice)
    {
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


        return Inertia::render('Modules/Invoices/Show', [

            "info" => [
                "invoice"         => $invoice,
                "client"          => $invoice->client,
                "invoice_item"    => $invoice->invoiceItems,
                'transactions'    => $transactions,
                "total_pay"       => $totalPay,
                "last_payment"    => $invoiceLastTransaction,
                'invoice_id'      => $invoice->created_at->format('Ymd').$invoice->id,
                'creator'         => $invoice->user,
                'payment_methods' => Method::all(),
                "created"         => $invoice->created_at->format('D, d F, Y'),
                'download_url'    => URL::route('invoices.generateInvoicePDFFile', $invoice->id),
                'payment_url'     => URL::route('transaction.index'),
            ]
        ]);

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function generateInvoicePDFFile($id){
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

//        return view('invoice.invoice', compact("data"));

        $pdf = Pdf::loadView('invoice.invoice', compact("data"));
        return $pdf->download('invoice.pdf');
    }


    public function edit($id){

        $invoice = CustomInvoice::findOrFail($id);

        return Inertia::render('Modules/Invoices/Edit', [
            "clients"   => Client::all(['id','name']),
            "info" => [
                "invoice"       => $invoice,
                "invoice_item"  => InvoiceItem::find($invoice->id)->get(),
                "update_url"    => URL::route('updateInvoices', $invoice->id),
            ]
        ]);
    }


    public function updateInvoice(Request $request, $id){

        $invoice = CustomInvoice::findOrFail($id);
        $invoice->update([
            'user_id'       => Auth::id(),
            'client_id'        => Request::input('client_id'),
            'subject'          => Request::input('subject'),
            'status'           => filled(Request::input('status')),
            'trams_and_condition' => Request::input('trams_and_condition'),
            'privicy_and_policy'  => Request::input('payment_policy'),
        ]);



//        $invoice->invoiceItems->createMany(Request::input('quatations'));

        foreach (Request::input('quatations') as $item){
            InvoiceItem::updateOrcreate([
                'invoice_id' => $invoice->id,
                'item_name'  => $item['item_name'],
                'price'      => $item['price'],
                'discount'   => $item['discount'],
            ]);
        }

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
        dd($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
