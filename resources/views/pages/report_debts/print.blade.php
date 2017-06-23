<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Report Debts</title>
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
                <div style="float: right">REPORT DEBTS</div>
            </th>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <thead>
            <tr>
                <th class="border-bottom text-left">NO</th>
                <th class="border-bottom text-left" style="width: 30%;">ORDER NO</th>
                <th class="border-bottom text-left">SUPPLIER</th>
                <th class="border-bottom text-left">PAID UNTIL</th>
                <th class="border-bottom text-right">TOTAL</th>
                <th class="border-bottom text-right">PAYMENT</th>
                <th class="border-bottom text-left">STATUS</th>
                <th class="border-bottom text-left">CREATED AT</th>
            </tr>
        </thead>
        <tbody>
        <?php $no = 1 ?>
        @foreach($orders as $order)
            <tr valign="top">
                <td>{{$no}}</td>
                <td>{{$order->no}}</td>
                <td>{{$order->supplier->name}}</td>
                <td>{{$order->paid_until_at->format('d M Y')}}</td>
                <td class="text-right">{{number_format($order->total)}}</td>
                <td class="text-right">{{number_format(abs($order->payment->total))}}</td>
                <td>{{$order->payment->total >= $order->total ? 'paid' : 'unpaid'}}</td>
                <td>{{$order->created_at->format('d M Y')}}</td>
            </tr>
            <?php $no++ ?>
        @endforeach
        </tbody>
    </table>
    <div class="text-left" style="width: 20cm;padding-left: 40px;">
        <h5>print at {{ date('d-m-Y') }} : {{auth()->user()->username}} | created : {{$order->employee->name}}</h5>
    </div>
</div>
<script>
    window.print();
</script>
</body>
</html>
