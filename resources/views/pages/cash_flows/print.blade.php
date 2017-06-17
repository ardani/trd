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
<div class="box box-info" style="font-family: monospace;">
    <div class="box-header with-border">
    </div>
    <div style="width: 20cm;text-align: center;padding-left: 40px;">
        <h4 style="margin-bottom: 5px;font-size: 16px">{{ setting('company.name') }}</h4>
        <div>{{ setting('company.address').' '.setting('company.phone')}}</div>
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
            <th class="border-bottom text-left" style="width: 25%;">CASH ACCOUNT</th>
            <th class="border-bottom text-left" style="width: 25%;">DATE</th>
        </tr>
        <tr valign="top">
            <td>{{request('account_code_id', 'all')}}</td>
            <td>{{request('date')}}</td>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <tbody>
        <tr>
            <th class="text-left border-bottom">No</th>
            <th class="text-left border-bottom">Account</th>
            <th class="border-bottom text-right">Debit</th>
            <th class="border-bottom text-right">Credit</th>
            <th class="border-bottom text-right">Saldo</th>
        </tr>
        @foreach($cashes as $detail)
            <tr valign="top">
                <td>{{$detail->id}}</td>
                <td>{{$detail->account_code->name}}</td>
                <td class="text-right">{{number_format($detail->sdebit)}}</td>
                <td class="text-right">{{number_format($detail->scredit)}}</td>
                <td class="text-right">{{number_format($detail->saldo)}}</td>
            </tr>
        @endforeach
        </tbody>
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