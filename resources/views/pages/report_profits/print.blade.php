<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Report Sales</title>
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
            <th class="text-left border-bottom">DATE : {{request('date')}}</th>
            <th class="text-left border-bottom">CUSTOMER : {{$customer}}</th>
            <th class="text-right border-bottom" style="text-transform: uppercase">REPORT SALES</th>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <thead>
            <tr>
                <th class="border-bottom text-left">NO</th>
                <th class="border-bottom text-left" style="width: 30%;">SALE</th>
                <th class="border-bottom text-left">PAYMENT INFO</th>
                <th class="border-bottom text-left">CASH</th>
                <th class="border-bottom text-right">DISC</th>
                <th class="border-bottom text-right">TOTAL</th>
                <th class="border-bottom text-left">DATE</th>
            </tr>
        </thead>
        <tbody>
        <?php $no = 1;$total = 0; ?>
        @foreach($sales as $sale)
            <tr valign="top">
                <td>{{$no}}</td>
                <td>{{$sale->no}}<br/>{{$sale->customer->name}}</td>
                <td>
                    {{$sale->payment_method->name}}<br/>
                    {!!$sale->paid_status ? 'paid <br/>' : ''!!}
                    @if ($sale->payment_method_id == 2 && $sale->paid_status != 1)
                        {{ is_null($sale->paid_until_at) ? '' : 'expire : '.$sale->paid_until_at->format('d/m/Y')}}
                    @endif
                </td>
                <td class="text-right">{{number_format($sale->cash)}}</td>
                <td class="text-right">{{number_format($sale->disc)}}</td>
                <td class="text-right">{{number_format($sale->total)}}</td>
                <td>{{$sale->created_at->format('d M Y')}}</td>
            </tr>
            <?php $no++; $total += $sale->total; ?>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5">TOTAL</td>
            <td class="text-right">{{number_format($total)}}</td>
            <td colspan="2"></td>
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
