{{--https://docraptor.com/try_it_out--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice PDF</title>
    <link rel="stylesheet" href="{{ ltrim(elixir('css/pdf-invoice.css'), '/') }}" />

    <style>
        body {
            font-size: 14px;
        }
        p {
            margin: 2px 0;
            font-size: 14px;
        }
        h3 {
            margin: 4px 0;
            font-size: 18px;
        }
        /*.container {*/
        /*    box-sizing: border-box;*/
        /*}*/
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
            height: 80px;
            padding-top: 15px;
        }
        table {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
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
            padding: 5px;
        }
        .main-table .border {
            border: 1px solid #44423f;
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
            margin-bottom: 50px;
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
            font-size: 10px;
        }
    </style>
</head>
<body>


<div id="container">
    <div class="row">
        <div class="col-1">
            <div id="logo">
                <img src="{{ public_path('creativeTechPark.png') }}" alt="Creative Tech Park" class="logo-img">
            </div>
        </div>
        <div class="col-1"></div>
        <div class="col-1">
            <div id="info">
                <h3>Creative Tech Park</h3>
                <p>Imperial Irish Kingdom, Mo-03 <br>(3rd Floor), Merul Badda, Dhaka 1212</p>
                <p>Phone: +8801639-200002</p>
                <p>Email: info@creativetechpark.com</p>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            <div class="to">
                <h3>Invoice To,</h3>
                <h3>{{ $data["client"]['name'] }}</h3>
                <p>Phone: {{ $data["client"]['phone'] }}</p>
                <p>Email: {{ $data["client"]['email'] }}</p>
                @if ($data["client"]['address'])
                    <p>Company: {{ $data["client"]['company'] }}</p>
                @endif
                @if ($data["client"]['address'])
                    <p>Address: {{ $data["client"]['address'] }}</p>
                @endif
            </div>
        </div>
        <div class="col-3">
            <div class="to">
                <h3>Invoice ID: CTP-{{ date('Yd', strtotime($data['invoice']['created_at'])) }}{{ $data['invoice']['id'] }}</h3>
                <p>Date: {{ date('D, d F, Y', strtotime($data['invoice']['created_at'])) }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <table class="main-table">
                <thead>
                <tr>
                    <th class="text-center">Description</th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Discount</th>
                    <th class="text-right">Total</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $subTotal = 0;
                    $discount = 0;
                @endphp
                @foreach ($data['invoice_item'] as $item)
                    @php
                        $subTotal += $item->price;
                        $discount += $item->discount;
                        $grandTotal = $subTotal - $discount;
                    @endphp
                    <tr>
                        <td class="border text-left">
                            {!! $item->item_name !!}
                        </td>
                        <td class="border text-center">
                            <strong>{{ $item->price }} Tk</strong>
                        </td>
                        <td class="border text-right">
                            <strong>{{ $item->discount ?? 0 }} Tk</strong>
                        </td>
                        <td class="border text-right">
                            <strong>{{ $grandTotal }} Tk</strong>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-right border" colspan="3">Sub Total</td>
                    <td class="text-right border"><strong>{{ $subTotal }} Tk</strong></td>
                </tr>
                <tr>
                    <td class="text-right border" colspan="3">Discount</td>
                    <td class="text-right border"><strong> - {{ $discount }} Tk</strong></td>
                </tr>
                <tr>
                    <td class="text-right border" colspan="3">Grand Total</td>
                    <td class="text-right border"><strong>{{ $subTotal - $discount }} Tk</strong></td>
                </tr>
                 <tr>
                    <td class="text-right border" colspan="3">Amount Paid</td>
                    <td class="text-right border"><strong>- {{ $data['total_pay']  }} Tk</strong></td>
                </tr>
                <tr>
                    <td class="text-right border" colspan="3">Total Due</td>
                    <td class="text-right border"><strong>{{ $grandTotal - $data['total_pay'] }} Tk</strong></td>
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

            <p id="inword"><strong>Inword:</strong> {{ $numberTransformer->toWords($grandTotal - isset($paid)) }} Taka Only.</p>
        </div>
    </div>
  {{--  <div class="row">
        <div class="col-3">
            <h3>Note:</h3>
            <span id="mb-20">
                Nots
            </span>
            <br>
            <br>
            <br>
        </div>
    </div>--}}

    <div class="row mt-3">
        <div class="col-3">
            <p class="text-center">This is an electronically generated document, no signature is required.</p>
            <p class="text-center">Created By {{ $data['creator']['name'] }}</p>
        </div>
    </div>
    <div class="page-break"></div>
    <div class="row">
        <div class="col-3">
            <h3>Payment Mehod:</h3>
            <p>Pay Direct to Our Corporate Bank Account</p>
            <table class="table" width="100%">
                <thead>
                <tr>
                    <th>Bank Name</th>
                    <th>Account Name</th>
                    <th>Account No</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>EASTERN BANK LTD</td>
                    <td>CREATIVE TECH PARK</td>
                    <td>1241070056170</td>
                </tr>
                <tr>
                    <td>THE CITY BANK LTD</td>
                    <td>CREATIVE TECH PARK</td>
                    <td>1502467424001</td>
                </tr>
                <tr>
                    <td>DUTCH BANGLA BANK LTD</td>
                    <td>CREATIVE TECH PARK</td>
                    <td>246.110.0005044</td>
                </tr>
                <tr>
                    <td>PREMIER BANK LTD</td>
                    <td>CREATIVE TECH PARK</td>
                    <td>13611100000331</td>
                </tr>
                </tbody>
            </table>
            <p><strong>Pay Using Our Merchant Mobile Banking Account:</strong></p>
            <p><strong>Bkash: 01639200002 (Payment)</strong></p>
            <ul>
                <li>Go to Your bKash Mobile Menu by dialing *247#</li>
                <li>Choose "Payment"</li>
                <li>Enter Merchant bKash Account Number 01639200002</li>
                <li>Enter the amount (Invoice Amount)</li>
                <li>Enter a reference (Invoice No/ Your Name)</li>
                <li>Enter Counter Number 0</li>
                <li>Now enter your bKash Mobile Menu PIN to Confirm!</li>
                <li>Done! You will receive a confirmation message from bKash*</li>
            </ul>
            <h3>Direct Payment Bill Online at <a href="https://creativetechpark.com/pay" >https://creativetechpark.com/pay</a></h3>
        </div>
    </div>
    @if ($data['invoice']['privicy_and_policy'])
        <div class="row">
            <div class="col-3">
                <h3>Payment Policy:</h3>
                {!! nl2br($data['invoice']['privicy_and_policy']) !!}
            </div>
        </div>
    @endif
    @if ($data['invoice']['trams_and_condition'])
        <div class="row mb-50">
            <div class="col-3">
                <h3>Terms of Service:</h3>
                {!! nl2br($data['invoice']['trams_and_condition']) !!}
            </div>
        </div>
    @endif
</div>
</body>
</html>






