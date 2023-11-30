<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice from Creative Tech Park</title>
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
            font-size: 16px;
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
        .col-3 {
            width: 100%;
            float: left;
        }
        .temp_content{
             word-wrap: break-word !important;
        }
    </style>
</head>
<body>
    <div id="container">
        <div class="row">
            <div class="col-3">
                <h3>Dear {{ $invoice->client?->name??  $invoice->quotation?->client?->name }},</h3>
                <p>Greetings from Creative Tech Park! Hope you are doing well.</p>
                <p>This is a notice that an invoice has been generated on {{ $invoice?->invoice_date?->format('l, F jS, Y')}}.</p>
                @if($template)
                    <p class="temp_content" style="margin-bottom: 1rem">{!! nl2br($template) !!}</p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
