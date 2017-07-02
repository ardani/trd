<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Report Profit</title>
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
            <th class="text-right border-bottom" style="text-transform: uppercase">REPORT PROFIT</th>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <thead>
            <tr>
                <th class="border-bottom text-left">Description</th>

                <th class="border-bottom text-left">Debit</th>
                <th class="border-bottom text-left">Credit</th>
                <th class="border-bottom text-left">Saldo</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td style="font-weight: bold">Income</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left: 25px">Sale</td>
            <td class="text-right" style="padding-left: 25px">{{number_format($sales_total)}}</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="font-weight: bold">Total Income</td>
            <td></td>
            <td></td>
            <td class="text-right">{{number_format($sales_total)}}</td>
            <?php $profit += $sales_total ?>
        </tr>
        <tr>
            <td style="font-weight: bold">HPP</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left: 25px">Persedian Awal</td>
            <td></td>
            <td class="text-right">{{number_format($first_stock)}}</td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left: 25px">Order</td>
            <td></td>
            <td class="text-right">{{number_format($order_total)}}</td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left: 25px">Persediaan Akhir</td>
            <td></td>
            <td class="text-right">{{number_format($last_stock)}}</td>
            <td></td>
        </tr>
        <tr>
            <td style="font-weight: bold">Total HPP</td>
            <td></td>
            <td></td>
            <td class="text-right">{{number_format($first_stock+$order_total-$last_stock)}}</td>
            <?php $profit += ($first_stock+$order_total-$last_stock) ?>
        </tr>
        <tr>
            <td style="font-weight: bold">Biaya Production</td>
            <td></td>
            <td></td>
            <td class="text-right">{{number_format($production_total)}}</td>
            <?php $profit += $production_total ?>
        </tr>
        <tr>
            <td style="font-weight: bold">Outcome</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php $total_cost = 0 ?>
        @foreach($costs as $cost)
            <tr>
                <td style="padding-left: 25px;">{{$cost->name}}</td>
                <td></td>
                <td class="text-right">{{number_format($cost->saldo)}}</td>
                <td></td>
            </tr>
            <?php $total_cost += $cost->saldo ?>
        @endforeach
        <tr>
            <td style="font-weight: bold">Total Cost</td>
            <td></td>
            <td></td>
            <td class="text-right">{{number_format($total_cost)}}</td>
            <?php $profit += $total_cost ?>
        </tr>
        <tr>
            <td style="font-weight: bold">Profit</td>
            <td></td>
            <td></td>
            <td class="text-right">{{number_format($profit)}}</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" style="padding-top: 20px">
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
