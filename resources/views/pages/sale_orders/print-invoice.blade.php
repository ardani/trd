<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Invoice Order</title>
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
<div class="box box-info" style="font-family: monospace;">
    <div class="box-header with-border">
    </div>
    <div style="width: 20cm;text-align: center;padding-left: 40px;">
        <h4 style="margin-bottom: 5px;font-size: 16px">{{ setting('company.name') }}</h4>
        <div>{{ setting('company.address').' '.setting('company.phone')}}</div>
    </div>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:10px;padding-left: 40px;">
        <tbody>
        <tr >
            <th class="text-left border-bottom" colspan="2" style="width: 50%;text-transform: uppercase">
                {{$sale->customer->name}}
                <div style="float: right">INVOICE</div>
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
            <th class="border-bottom text-left" style="width: 20%;">PAYMENT</th>
            <th class="border-bottom text-left" style="width: 20%;">PAYMENT UNTIL</th>
        </tr>
        <tr valign="top">
            <td>{{$sale->no}}</td>
            <td>{{$sale->created_at->format('d M Y')}}</td>
            <td>{{$sale->payment_method->name}}</td>
            <td>{{$sale->paid_until_at ? $sale->paid_until_at->format('d M Y') : '-'}}</td>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <tbody>
        <tr>
            <th class="text-left border-bottom">No</th>
            <th class="text-left border-bottom">Product</th>
            <th class="border-bottom">Price</th>
            <th class="border-bottom">Disc</th>
            <th class="text-left border-bottom">Unit</th>
            <th class="border-bottom">Qty</th>
            <th class="border-bottom">Subtotal</th>
        </tr>
        @foreach($sale->transactions as $transaction)
        <tr valign="top">
            <td>{{$transaction->product->code}}</td>
            <td>{{$transaction->product->name.' - '.$transaction->desc}}</td>
            <td class="text-right">{{number_format($transaction->selling_price)}}</td>
            <td class="text-right">{{$transaction->disc}}</td>
            <td>{{$transaction->units}}</td>
            <td class="text-right">{{abs($transaction->qty)}}</td>
            <td class="text-right">{{number_format(abs($transaction->qty)*($transaction->selling_price - $transaction->disc))}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:35px;padding-left: 40px;">
        <tbody>
        <tr valign="top">
            <td class="border-bottom" colspan="2"></td>
        </tr>
        <tr valign="top">
            <td class="text-right" style="width: 85%">Total :</td>
            <td class="text-right">{{number_format($sale->total)}}</td>
        </tr>
        <tr valign="top">
            <td class="text-right">Disc :</td>
            <td class="text-right">{{number_format($sale->disc)}}</td>
        </tr>
        <tr valign="top">
            <td class="text-right">Dp :</td>
            <td class="text-right">{{number_format($sale->cash)}}</td>
        </tr>
        <tr valign="top" class="border-bottom">
            <td class="border-bottom text-right"><b>Grand Total :</b></td>
            <td class="border-bottom text-right">{{number_format($sale->cash - $sale->total - $sale->disc )}}</td>
        </tr>
        </tbody>
    </table>
    <div class="text-left" style="width: 20cm;padding-left: 40px;">
        <h5>print at {{ date('d-m-Y') }} by {{$sale->employee->name}}</h5>
    </div>
</div>
<script>
    window.print();
</script>
</body>
</html>