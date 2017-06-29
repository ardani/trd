<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cash Out</title>
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
                <div style="float: right">CASH OUT</div>
            </th>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <tbody>
        <tr>
            <th class="text-left border-bottom">No</th>
            <th class="text-left border-bottom">Account</th>
            <th class="border-bottom">Credit</th>
            <th class="border-bottom">Note</th>
            <th class="border-bottom">Created At</th>
        </tr>
        <?php $no = 1; $total = 0;?>
        @foreach($cashes as $cash)
            <tr valign="top">
                <td class="border-bottom">{{$no}}</td>
                <td class="border-bottom">{{$cash->no}}</td>
                <td class="text-right border-bottom">{{number_format($cash->total)}}</td>
                <td class="border-bottom"></td>
                <td class="border-bottom">{{$cash->created_at->format('d M Y')}}</td>
            </tr>
            <?php $no++; $total += $cash->total ?>
            @foreach($cash->details as $detail)
                <tr valign="top">
                    <td>-</td>
                    <td>{{$detail->account_code->name}}</td>
                    <td class="text-right">{{number_format($detail->credit)}}</td>
                    <td>{{$detail->note}}</td>
                    <td></td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2" class="border-top"><strong>Total</strong></td>
            <td class="text-right border-top">{{number_format($total)}}</td>
            <td colspan="2" class="border-top"></td>
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