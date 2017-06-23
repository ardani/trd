<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cash In</title>
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
                <div style="float: right">CASH FLOW</div>
            </th>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <tbody>
        <tr>
            <th class="border-bottom text-left" style="width: 25%;">ACCOUNT</th>
            <th class="border-bottom text-left" style="width: 25%;">DATE</th>
        </tr>
        <tr valign="top">
            <td>{{request('account_code_id', 'all')}}</td>
            <td>{{request('date')}}</td>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <thead>
            <tr>
                <th class="text-left border-bottom">No</th>
                <th class="text-left border-bottom">Created</th>
                <th class="text-left border-bottom">Cash No</th>
                <th class="text-left border-bottom">Account</th>
                <th class="text-left border-bottom">Note</th>
                <th class="text-left border-bottom">Giro</th>
                <th class="border-bottom text-right">Debit</th>
                <th class="border-bottom text-right">Credit</th>
                <th class="border-bottom text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="6"><strong>Cash Flow Before</strong></td>
            <td class="text-right">{{number_format($cashes['last']['debit'])}}</td>
            <td class="text-right">{{number_format($cashes['last']['credit'])}}</td>
            <td class="text-right">{{number_format($cashes['last']['saldo'])}}</td>
        </tr>
        <?php $saldo = $cashes['last']['saldo']; $no = 1?>
        @foreach($cashes['present'] as $cash)
            <?php $saldo += ($cash->debit - $cash->credit) ?>
            <tr valign="top">
                <td>{{$no}}</td>
                <td>{{$cash->created_at->format('d/M/Y')}}</td>
                <td>{{$cash->cash_id ? $cash->cash->no : '-'}}</td>
                <td>{{$cash->account_code->name}}</td>
                <td>{{$cash->note}}</td>
                <td>{{$cash->giro}}</td>
                <td class="text-right">{{number_format($cash->debit)}}</td>
                <td class="text-right">{{number_format($cash->credit)}}</td>
                <td class="text-right">{{number_format($saldo)}}</td>
            </tr>
            <?php $no++ ?>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="8"><strong>Last Saldo</strong></td>
            <td class="text-right">{{number_format($saldo)}}</td>
        </tr>
        </tfoot>
    </table>
    <div class="text-left" style="width: 20cm;padding-left: 40px;">
        <h5>print by {{auth()->user()->username}} at {{ date('d-m-Y') }} </h5>
    </div>
</div>
<script>
    window.print();
</script>
</body>
</html>