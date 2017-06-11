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
<div class="box box-info" style="font-family: monospace;">
    <div class="box-header with-border">
    </div>
    <div style="width: 20cm;text-align: center;padding-left: 40px;">
        <h4 style="margin-bottom: 5px">{{ setting('company.name') }}</h4>
        <div>{{ setting('company.address').' '.setting('company.phone')}}</div>
    </div>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:10px;padding-left: 40px;">
        <tbody>
        <tr >
            <th class="text-left border-bottom" colspan="2" style="width: 50%;text-transform: uppercase">
                <div style="float: right">REPORT PAYABLES</div>
            </th>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <thead>
        <tr>
            <th class="border-bottom text-left" style="width: 30%;">NO</th>
            <th class="border-bottom text-left">CUSTOMER</th>
            <th class="border-bottom text-left">PAID UNTIL</th>
            <th class="border-bottom text-right">TOTAL</th>
            <th class="border-bottom text-right">PAYMENT</th>
            <th class="border-bottom text-left">STATUS</th>
            <th class="border-bottom text-left">CREATED AT</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sales as $sale)
            <tr valign="top">
                <td>{{$sale->no}}</td>
                <td>{{$sale->customer->name}}</td>
                <td>{{$sale->paid_until_at->format('d M Y')}}</td>
                <td class="text-right">{{number_format($sale->total)}}</td>
                <td class="text-right">{{number_format(abs($sale->payment->total))}}</td>
                <td>{{$sale->payment->total >= $sale->total ? 'paid' : 'unpaid'}}</td>
                <td>{{$sale->created_at->format('d M Y')}}</td>
            </tr>
        @endforeach
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
