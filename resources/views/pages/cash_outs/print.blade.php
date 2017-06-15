<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Report Cash Outs</title>
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
                <div style="float: right">REPORT CASH OUT</div>
            </th>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="width: 20cm;margin-top:15px;padding-left: 40px;">
        <thead>
            <tr>
                <th class="border-bottom text-left">ACCOUNT</th>
                <th class="border-bottom text-left">PAY FROM</th>
                <th class="border-bottom text-right">AMOUNT</th>
                <th class="border-bottom text-left">NOTE</th>
                <th class="border-bottom text-left">GIRO</th>
                <th class="border-bottom text-left">CREATED AT</th>
            </tr>
        </thead>
        <tbody>
        @foreach($cashs as $row)
            <?php
                $ref = $row->account_ref_id ? $row->account_code_ref_id.' - '.$row->account_code_ref->name : '-'
            ?>
            <tr valign="top">
                <td>{{$row->account_code_id .' - '.$row->account_code->name}}</td>
                <td>{{$ref}}</td>
                <td class="text-right">{{number_format($row->value)}}</td>
                <td>{{$row->note}}</td>
                <td>{{$row->giro}}</td>
                <td>{{$row->created_at->format('d M Y')}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="text-left" style="width: 20cm;padding-left: 40px;">
        <h5>print at {{ date('d-m-Y') }} by {{auth()->user()->username}}</h5>
    </div>
</div>
<script>
    window.print();
</script>
</body>
</html>
