<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Quotation Invoice PDF</title>
    <style>
        body {
            font-size: 14px;
            font-family: Arial, serif !important;
        }

        p {
            margin: 2px 0;
            font-size: 14px;
        }

        h3 {
            margin: 4px 0;
            font-size: 18px;
        }

        .container {
            box-sizing: border-box;
        }

        .row {
            width: 100%;
            display: flex;
            clear: both;
            margin-bottom: 1em;
        }

        .col-1 {
            width: 33.33%;
            float: left;
        }

        .col-2 {
            width: 66.66%;
            float: left;
        }

        .col-3 {
            width: 100%;
            float: left;
        }

        .spacer {
            width: 100%;
            border-bottom: 1px solid #44423f;
        }

        .logo-img {
            max-width: 100%;
            height: 60px;
            padding-top: 15px;
        }

        table {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
            border: 1px solid #e7e7e7;
        }

        .main-table {
            margin-bottom: 20px;
        }

        thead {
            background: #efefef;
            text-align: left;
        }

        th {
            padding: 10px;
        }

        td {
            padding: 0 10px;
            border-right: 1px solid #e7e7e7;
        }

        td:nth-child(2) {
            border-right: 0 !important;
        }
        td:nth-child(3) {
            border-right: 0 !important;
        }

        .main-table .border {
            /*border: 1px solid #44423f;*/
        }
        .trx-table tr td{
            border-right: 1px solid #e7e7e7 !important;
        }

        .no-border-btm {
            border-bottom: 0;
        }

        .mt-3 {
            margin-top: 3em;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        #inword {
            text-transform: capitalize;
            margin-bottom: 20px;
        }

        .to {
            margin-bottom: 10px;
        }

        #mb-20 {
            margin-bottom: 50px;
        }

        .page-break {
            page-break-after: always;
        }

        small {
            font-size: 14px;
        }
    </style>
</head>
<body>

{{--{{ dd($data)  }}--}}
<div id="container">
    <div class="row">
        <div class="col-1">
            <div id="logo">
                @if($isPrint)
                    <img src="{{ asset('images/creativeTechPark.png') }}" alt="Creative Tech Park" class="logo-img">
                @else
                    <img src="{{ public_path('creativeTechPark.png') }}" alt="Creative Tech Park" class="logo-img">
                @endif
            </div>
        </div>
        <div class="col-1"></div>
        <div class="col-1">
            <div id="info" style="text-align: right">
                <h3>Creative Tech Park</h3>
                <p>Imperial Irish Kingdom, Mo-03 <br>(3rd Floor), Merul Badda, Dhaka 1212</p>
                <p>Phone: +8801639-200002</p>
                <p>Email: info@creativetechpark.com</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-1">
            <div class="to">
                <h3>Invoice To,</h3>
                @if($invoice->quotation)
                    <h4 style="margin: 0; padding: 0">{{ $invoice->quotation?->client?->name }}</h4>
                    <p>Phone: {{ $invoice->quotation?->client?->phone }}</p>
                    <p>Email: {{ $invoice->quotation?->client?->email }}</p>
                    @if ($invoice->quotation?->client?->address)
                        <p>Company: {{ $invoice->quotation?->client?->company }}</p>
                    @endif
                    @if ($invoice->quotation?->client?->address)
                        <p>Address: {{ $invoice->quotation?->client?->name }}</p>
                    @endif
                @else
                    <h4 style="margin: 0; padding: 0">{{ $invoice?->client?->name }}</h4>
                    <p>Phone: {{ $invoice->client?->phone }}</p>
                    <p>Email: {{ $invoice->client?->email }}</p>
                    @if ($invoice->client?->address)
                        <p>Company: {{ $invoice->client?->company }}</p>
                    @endif
                    @if ($invoice->client?->address)
                        <p>Address: {{ $invoice->client?->name }}</p>
                    @endif
                @endif
            </div>
        </div>

        <div class="col-1"></div>
        <div class="col-1">
            <div class="to" style="text-align: right">
                <h3>ID:{{ env('INV_PREFIX') }}{{ $invoice->invoice_id }}{{ $invoice->id }}</h3>
                <p>Date: {{ $invoice?->invoice_date?->format('d-m-Y') }}</p>
            </div>
        </div>
    </div>

    {{--    <div class="row" style="margin: 0">
        <h2 style="margin: 0">
            Subject: {{ $invoice->quotation?->subject }}
        </h2>
    </div>--}}
    <div class="row">
        <div class="col-3">
            <table class="main-table">
                <thead>
                <tr>
                    <th class="text-left">Description</th>
                    <th class="text-center"></th>
                    <th class="text-center"></th>
                    <th class="text-right">Total</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($pref as $item)
{{--                    <tr @if($loop->last) style="border-bottom:1px solid #e7e7e7" @endif>--}}
                    <tr style="border-bottom:1px solid #e7e7e7">
                        <td class="border text-left"  colspan="3" @if($loop->last) style="padding-bottom: 7px" @endif>
                            {!! nl2br($item['name']) !!}
                        </td>
                        <td class="border text-right" @if($loop->last) style="padding-bottom: 7px" @endif>
                            <p>{{ ($item['price'] * $item['qty']) ?? 0 }}</p>
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td class="text-right border" colspan="3">Sub Total</td>
                    <td class="text-right border"><strong>{{ $invoice->total_price  }}</strong></td>
                </tr>
                @if($invoice->discount > 0)
                    <tr>
                        <td class="text-right border" colspan="3">Discount</td>
                        <td class="text-right border"><strong> {{ $invoice->discount ?? 0  }}</strong></td>
                    </tr>
                @endif
                <tr style="border-top: 1px solid #e7e7e7">
                    <td class="text-right border" style="padding: 10px" colspan="3"><strong>Grand Total</strong></td>
                    <td class="text-right border" style="padding: 10px"><strong>{{ $invoice->grand_total }}</strong></td>
                </tr>
                <tr>
                    <td class="text-right border" colspan="3">Total Pay</td>
                    <td class="text-right border"><strong> {{ $invoice->pay ?? 0  }}</strong></td>
                </tr>
                <tr style="border-top: 1px solid #e7e7e7">
                    <td class="text-right border" style="padding: 10px; font-weight: bolder; font-size: 18px" colspan="3">Total Due</td>
                    <td class="text-right border" style="padding: 10px; font-weight: bolder; font-size: 18px" ><strong>{{ $invoice->due }}</strong></td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            @php
                $numberToWords = new NumberToWords\NumberToWords;
                $numberTransformer = $numberToWords->getNumberTransformer('en');
            @endphp
            <p id="inword">
                <strong>Inword:</strong>
                {{ $numberTransformer->toWords( $invoice->due ?? 0 ) }} {{ $invoice?->currency ?? 'Taka' }} Only.
            </p>
        </div>
    </div>

    @if($invoice->note)
        <div class="row">
            <div class="col-3">
                <h3>Note:</h3>
                <span style="margin-bottom: 20px">
                      {!! nl2br($invoice->note ?? ' ') !!}
                  </span>
                <br>
                <br>
            </div>
        </div>
    @endif
    <div class="row mt-3">
        <div class="col-3 text-center">
            <p class="text-center">Created By {{ $invoice->user?->name }}</p>
            <p>{{ config('app.electrically_generated_message') }}</p>
        </div>
    </div>





    @if($invoice?->transactions->count())
        <div class="row" style="margin-top: 3rem;">
        <div class="col-3">
            <h3>Payment Details:</h3>
            <table class="trx-table">
                <thead>
                <tr>
                    <th class="text-left">Trx Id</th>
                    <th class="text-center">Trx Date</th>
                    <th class="text-center">Total Pay</th>
                    <th class="text-center">Total Due</th>
                    <th class="text-right">Payment Method</th>
                </tr>
                </thead>
                <tbody>
                @forelse($invoice?->transactions as $item)
                    @if($item)
                        <tr style="border-bottom:1px solid #e7e7e7">
                            <td class="border text-left">
                                Trx_{{ $item?->transaction_id }}
                            </td>
                            <td class="border text-center">
                                {{ $item?->payment_date->format('d-m-Y')}}
                            </td>
                            <td class="border text-center">
                                {{ $item?->pay ?? '---'}}
                            </td>
                            <td class="border text-center">
                                {{ $item?->due ?? '---'}}
                            </td>
                            <td class="border text-right">
                                {{ $item?->method?->name  ?? '---'}}
                            </td>
                        </tr>
                    @endif
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif


    <div class="page-break"></div>
    <div class="row">
        <div class="col-3">
            @if (!is_null($invoice?->payment_methods ?? $invoice?->quotation?->payment_methods))
                <h3>Payment Mehod:</h3>
                {!! nl2br($invoice?->payment_methods ?? $invoice?->quotation?->payment_methods) !!}
                <h3>Direct Payment Bill Online at <a href="https://creativetechpark.com/pay" >https://creativetechpark.com/pay</a></h3>
            @endif
        </div>
    </div>
    @if (!is_null($invoice?->payment_policy ?? $invoice?->quotation?->payment_policy))
        <div class="row">
            <div class="col-3">
                <h3>Payment Policy:</h3>
                {!! nl2br($invoice?->payment_policy  ?? $invoice->quotation->payment_policy) !!}
            </div>
        </div>
    @endif

    @if (!is_null($invoice?->trams_of_service ?? $invoice?->quotation?->trams_of_service))
        <div class="row mb-50">
            <div class="col-3">
                <h3>Terms of Service:</h3>
                {!! nl2br($invoice?->trams_of_service ?? $invoice?->quotation?->trams_of_service) !!}
            </div>
        </div>
    @endif
</div>


<script>
    if ({{ $isPrint }}){
        document.addEventListener('DOMContentLoaded', function() {
            window.print();
        });
    }
</script>


</body>
</html>






