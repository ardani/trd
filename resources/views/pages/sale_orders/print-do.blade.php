<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Delivery Order</title>
    <style>
        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .border-bottom {
            border-bottom: 1px dotted #000
        }
    </style>
</head>
<body>
<div class="box box-info" style="font-family: 'monospace';">
    <div class="box-header with-border">
    </div>
    <div style="width: 20cm;text-align: center;padding-left: 40px;">
        <h4 style="margin-bottom: 5px">{{ setting('company.name') }}</h4>
        <div>{!!setting('company.address')!!}</div>
    </div>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:10px;padding-left: 40px;">
        <tbody>
        <tr>
            <th class="text-left border-bottom" colspan="2" style="width: 50%;text-transform: uppercase">
                {{$sale->customer->name}}
                <div style="float:right">Delivery Order</div>
            </th>
        </tr>
        <tr valign="top">
            <td style="width: 5%;">Address</td>
            <td style="width: 40%;">: {{$sale->customer->address}}</td>
        </tr>
        <tr valign="top">
            <td style="width: 5%;">Phone</td>
            <td style="width: 40%;">: {{$sale->customer->phone}}</td>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <tbody>
        <tr>
            <th class="border-bottom text-left" style="width: 30%;">PO NUMBER</th>
            <th class="border-bottom text-left" style="width: 20%;">CREATED AT</th>
        </tr>
        <tr valign="top">
            <td>{{$sale->no}}</td>
            <td>{{$sale->created_at->format('d M Y')}}</td>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <tbody>
        <tr>
            <th class="text-left border-bottom">No</th>
            <th class="text-left border-bottom">Product</th>
            <th class="text-left border-bottom">Unit</th>
            <th class="border-bottom">Qty</th>
        </tr>
        @foreach($sale->transactions as $transaction)
            <tr valign="top">
                <td>{{$transaction->product->code}}</td>
                <td>{{$transaction->product->name.' - '.$transaction->desc}}</td>
                <td>{{$transaction->units}}</td>
                <td class="text-right">{{abs($transaction->qty)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:35px;padding-left: 40px;">
        <thead>
            <tr>
                <th>Diterima</th>
                <th>Dikirim</th>
                <th>Diperiksa</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border-bottom" style="width: 33%;height: 3cm;padding: 2px"></td>
                <td class="border-bottom" style="width: 33%;height: 3cm"></td>
                <td class="border-bottom" style="width: 33%;height: 3cm"></td>
            </tr>
        </tbody>
    </table>
    <div class="text-left" style="width: 20cm;padding-left: 40px;">
        <h5>created by : {{$sale->employee->name}} print by {{auth()->user()->username}} {{ date('d-m-Y') }}</h5>
    </div>
</div>
<script>
    window.print();
</script>
</body>
</html>