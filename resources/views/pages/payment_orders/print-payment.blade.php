<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Payment Order</title>
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
        <tr >
            <th class="text-left border-bottom" colspan="2" style="width: 50%;text-transform: uppercase">
                {{$order->supplier->name}}
                <div style="float: right">PAYMENT ORDER</div>
            </th>
        </tr>
        <tr valign="top">
            <td style="width: 5%;">Address</td>
            <td style="width: 40%;">: {{$order->supplier->address}}</td>
        </tr>
        <tr valign="top">
            <td style="width: 5%;">Phone</td>
            <td style="width: 40%;">: {{$order->supplier->phone}}</td>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <tbody>
        <tr>
            <th class="border-bottom text-left" style="width: 30%;">ORDER NO</th>
            <th class="border-bottom text-left" style="width: 20%;">INVOICE NO</th>
            <th class="border-bottom text-left" style="width: 20%;">DO NO</th>
            <th class="border-bottom text-left" style="width: 20%;">CREATED AT</th>
            <th class="border-bottom text-left" style="width: 20%;">PAYMENT</th>
        </tr>
        <tr valign="top">
            <td>{{$order->no}}</td>
            <td>{{$order->invoice_no}}</td>
            <td>{{$order->delivery_order_no}}</td>
            <td>{{$order->created_at->format('d M Y')}}</td>
            <td>{{$order->payment_method->name}}</td>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <tbody>
        <tr>
            <th class="text-left border-bottom">No</th>
            <th class="text-left border-bottom" width="15%">Created At</th>
            <th class="text-left border-bottom">Amount</th>
            <th class="text-left border-bottom">Note</th>
            <th class="text-left border-bottom">Giro</th>
        </tr>
        <?php $total = 0; $no = 1 ?>
        @foreach($order->payment->detail as $row)
            <tr valign="top">
                <td>{{$no}}</td>
                <td>{{$row->created_at->format('d M Y')}}</td>
                <td class="text-right">{{number_format(abs($row->debit-$row->credit))}}</td>
                <td>{{$row->note}}</td>
                <td>{{$row->giro}}</td>
            </tr>
            <?php $total += abs($row->debit - $row->credit); $no++ ?>
        @endforeach
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:35px;padding-left: 40px;">
        <tbody>
        <tr valign="top">
            <td class="border-bottom" colspan="2"></td>
        </tr>
        <tr valign="top">
            <td class="text-right" style="width: 85%">Total Order:</td>
            <td class="text-right">{{number_format($order->total)}}</td>
        </tr>
        <tr valign="top" class="border-bottom">
            <td class="border-bottom text-right"><b>Total Payment:</b></td>
            <td class="border-bottom text-right">{{number_format($total)}}</td>
        </tr>
        </tbody>
    </table>
    <div class="text-left" style="width: 20cm;padding-left: 40px;">
        <h5>print at {{ date('d-m-Y') }} by {{$order->employee->name}}</h5>
    </div>
</div>
<script>
    window.print();
</script>
</body>
</html>