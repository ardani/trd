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
        .border-top {
            border-top: 1px dotted #000
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
            <th class="text-left border-bottom">
                DATE {{request('date')}}
            </th>
            <th class="text-left border-bottom" style="text-transform: uppercase">
                <div style="float: right">PAYMENT ORDER</div>
            </th>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <tbody>
        <tr>
            <th class="text-left border-bottom">No</th>
            <th class="text-left border-bottom">Account</th>
            <th class="border-bottom">Total</th>
            <th class="border-bottom">Payment</th>
            <th class="border-bottom">Status</th>
            <th class="border-bottom">Created At</th>
        </tr>
        <?php $no = 1; $total = 0;?>
        @foreach($orders as $order)
            <tr valign="top">
                <td class="border-bottom">{{$no}}</td>
                <td class="border-bottom">{!!$order->no.'<br/>'.$order->supplier->name !!}</td>
                <td class="text-right border-bottom">{{number_format($order->total)}}</td>
                <td class="border-bottom text-right">{{number_format($order->payment->total)}}</td>
                <td class="border-bottom">{{$order->paid_status ? 'paid' : 'unpaid'}}</td>
                <td class="border-bottom">{{$order->created_at->format('d M Y')}}</td>
            </tr>
            @foreach($order->payment->detail as $detail)
                <tr>
                    <td>-</td>
                    <td>{{$detail->account_code->name}}</td>
                    <td></td>
                    <td class="text-right">{{number_format(abs($detail->debit-$detail->credit))}}</td>
                    <td></td>
                    <td>{{$detail->created_at->format('d M Y')}}</td>
                </tr>
            @endforeach
            <?php $no++; $total += $order->payment->total ?>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" class="border-top"><strong>Total</strong></td>
            <td class="text-right border-top">{{number_format($total)}}</td>
            <td colspan="3" class="border-top"></td>
        </tr>
        <tr>
            <td colspan="9" style="padding-top: 20px">
                Print at {{ date('d-m-Y') }} : {{auth()->user()->username}}
            </td>
        </tr>
        </tfoot>
    </table>
</div>
<script>
    window.print();
</script>
</body>
</html>