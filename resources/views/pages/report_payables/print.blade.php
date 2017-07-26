<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Report Payables</title>
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
            <th class="text-left border-bottom">DATE : {{request('date')}}</th>
            <th class="text-left border-bottom">CUSTOMER : {{$customer}}</th>
            <th class="text-left border-bottom">STATUS : {{$status}}</th>
            <th class="text-right border-bottom" style="text-transform: uppercase">REPORT PAYABLES</th>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <thead>
        <tr>
            <th class="border-bottom text-left">NO</th>
            <th class="border-bottom text-left" style="width: 25%;">SALE NO</th>
            <th class="border-bottom text-left">PAID UNTIL</th>
            <th class="border-bottom text-right">DAYS</th>
            <th class="border-bottom text-right">TOTAL</th>
            <th class="border-bottom text-right">PAYMENT</th>
            <th class="border-bottom text-left">STATUS</th>
            <th class="border-bottom text-left">DATE</th>
        </tr>
        </thead>
        <tbody>
        <?php $no = 1; $total = 0; $payment = 0;?>
        @foreach($sales as $sale)
            <?php $shop = $sale->shop_id ? $sale->shop->name : '-' ?>
            <tr valign="top">
                <td class="border-top">{{$no}}</td>
                <td class="border-top">{{$sale->no}} - {{$shop}}<br/> {{$sale->customer->name}}</td>
                <td class="border-top">{{$sale->paid_until_at->format('d M Y')}}</td>
                <td class="text-right border-top">{{$sale->paid_until_at->diffInDays($now, false)}}</td>
                <td class="text-right border-top">{{number_format($sale->total)}}</td>
                <td class="text-right border-top">{{number_format(abs($sale->payment->total))}}</td>
                <td class="border-top">{{$sale->paid_status ? 'paid' : 'unpaid'}}</td>
                <td class="border-top">{{$sale->created_at->format('d M Y')}}</td>
            </tr>
            @foreach($sale->payment->detail as $detail)
                <tr>
                    <td>-</td>
                    <td>{{$detail->account_code->name}}</td>
                    <td colspan="3"></td>
                    <td class="text-right">{{number_format(abs($detail->debit-$detail->credit))}}</td>
                    <td></td>
                    <td>{{$detail->created_at->format('d M Y')}}</td>
                </tr>
            @endforeach
            <?php
            $no++;
            $total += $sale->total;
            $payment += abs($sale->payment->total);
            ?>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5" class="border-top">TOTAL</td>
            <td class="text-right border-top">{{number_format($total)}}</td>
            <td class="text-right border-top">{{number_format($payment)}}</td>
            <td class="text-right border-top">{{number_format($total-$payment)}}</td>
            <td class="text-right border-top"></td>
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
