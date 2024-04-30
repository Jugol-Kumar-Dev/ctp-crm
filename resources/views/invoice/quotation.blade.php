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
                <h3>Quotation To,</h3>
                <h4 style="margin: 0; padding: 0">{{ $quotation->client->name }}</h4>
                <p>Phone: {{ $quotation->client->phone }}</p>
                <p>Email: {{ $quotation->client->email }}</p>
                @if ($quotation->client->address)
                    <p>Company: {{ $quotation->client->company }}</p>
                @endif
                @if ($quotation->client->address)
                    <p>Address: {{ $quotation->client->name }}</p>
                @endif
            </div>
        </div>

        <div class="col-1"></div>
        <div class="col-1">
            <div class="to" style="text-align: right">
                <h3>ID:{{ env('QUT_PREFIX')}}{{ $quotation->quotation_id }}{{ $quotation->id }}</h3>
                <p>Created on: {{ $quotation->qut_date?->format('d-m-Y') }}</p>
                <p>Valid until: {{ $quotation->due_date?->format('d-m-Y') }}</p>
            </div>
        </div>
    </div>


    @if($quotation->subject)
        <div class="row" style="margin: 0">
            <h2 style="margin: 0">
                Subject: {{ $quotation->subject }}
            </h2>
        </div>
    @endif

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
                            @if(isset($item['packageName']))
                                <h4 style="margin:0;border-bottom:1px dashed #e7e7e7;display:inline;">{{ $item['packageName'] ?? '' }}</h4>
                                </br>
                            @endif
                            {!! nl2br($item['name']) !!}
                        </td>
                        <td class="border text-right" @if($loop->last) style="padding-bottom: 7px" @endif>
                            <p>{{ $item['price'] ?? 0 }} * {{ $item['qty'] ?? 1 }}</p>
                        </td>
                    </tr>
                @endforeach


                    <tr>
                        <td class="text-right border" colspan="3">Sub Total</td>
                        <td class="text-right border"><strong>{{ $quotation->total_price  }}</strong></td>
                    </tr>
                @if($quotation->discount > 0)
                    <tr>
                        <td class="text-right border" colspan="3">Discount</td>
                        <td class="text-right border"><strong> {{ $quotation->discount  }}</strong></td>
                    </tr>
                @endif

                    <tr style="border-top: 1px solid #e7e7e7">
                        <td class="text-right border" style="padding: 10px; font-weight: bolder; font-size: 18px" colspan="3">Grand Total</td>
                        <td class="text-right border" style="padding: 10px; font-weight: bolder; font-size: 18px" ><strong>{{ $quotation->grand_total }}</strong></td>
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
                {{ $numberTransformer->toWords($quotation->grand_total) }} {{ $quotation->currency }} Only.
            </p>
        </div>
    </div>

    @if($quotation?->note)
    <div class="row">
          <div class="col-3">
              <h3>Note:</h3>
              <span style="margin-bottom: 20px">
                  {!! nl2br($quotation->note ?? ' ') !!}
              </span>
              <br>
              <br>
          </div>
      </div>
    @endif

    <div class="row mt-3">
        <div class="col-3 text-center">
            <p class="text-center">Created By {{ $quotation->user->name }}</p>
            <p>{{ config('app.electrically_generated_message') }}</p>
        </div>
    </div>
    <div class="page-break"></div>
    <div class="row">
        <div class="col-3">
            @if (!is_null($quotation->payment_methods))
                <h3>Payment Mehod:</h3>
                {!! nl2br($quotation->payment_methods) !!}
                <h3>Direct Payment Bill Online at <a href="https://creativetechpark.com/pay" target="_blank">https://creativetechpark.com/pay</a></h3>
            @endif
        </div>
    </div>
    @if (!is_null($quotation->payment_policy))
        <div class="row">
            <div class="col-3">
                <h3>Payment Policy:</h3>
                {!! nl2br($quotation->payment_policy) !!}
            </div>
        </div>
    @endif

    @if (!is_null($quotation->trams_of_service))
        <div class="row mb-50">
            <div class="col-3">
                <h3>Terms of Service:</h3>
                {!! nl2br($quotation->trams_of_service) !!}
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






