<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Invoice Order</title>
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
    <script src="{{asset('js/rsvp.min.js')}}"></script>
    <script src="{{asset('js/sha256.min.js')}}"></script>
    <script src="{{asset('js/qz-tray.js')}}"></script>
</head>
<body>
<div class="box box-info" style="font-family: 'sans-serif';">
    <div class="box-header with-border">
    </div>
    <div style="text-align: center;">
        <h4 style="margin-bottom: 5px">{{ setting('company.name') }}</h4>
        <div>{!!setting('company.address')!!}</div>
    </div>
    <table class="table table-bordered table-striped" style="margin-top:10px;" width="100%">
        <tbody>
        <tr>
            <th class="text-left border-bottom" colspan="2" style="text-transform: uppercase">
                {{$sale->customer->name}}
            </th>
            <th class="text-right border-bottom">INVOICE</th>
        </tr>
        <tr valign="top">
            <td style="width: 5%;">Address</td>
            <td colspan="2" style="width: 40%;">: {{$sale->customer->address}}</td>
        </tr>
        <tr valign="top">
            <td style="width: 5%;">Phone</td>
            <td colspan="2" style="width: 40%;">: {{$sale->customer->phone}}</td>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style=";margin-top:15px;" width="100%">
        <tbody>
        <tr>
            <th class="border-bottom text-left" style="width: 30%;">PO NUMBER</th>
            <th class="border-bottom text-left" style="width: 20%;">CREATED AT</th>
            <th class="border-bottom text-left" style="width: 20%;">PAYMENT</th>
            <th class="border-bottom text-left" style="width: 20%;">PAYMENT UNTIL</th>
        </tr>
        <tr valign="top">
            <td>{{$sale->no}}</td>
            <td>{{$sale->created_at->format('d M Y')}}</td>
            <td>{{$sale->payment_method->name}}</td>
            <td>{{$sale->paid_until_at ? $sale->paid_until_at->format('d M Y') : '-'}}</td>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="margin-top:15px;" width="100%">
        <tbody>
        <tr>
            <th class="text-left border-bottom">No</th>
            <th class="text-left border-bottom">Product</th>
            <th class="border-bottom">Price</th>
            <th class="border-bottom">Disc</th>
            <th class="text-left border-bottom">Unit</th>
            <th class="border-bottom">Qty</th>
            <th class="border-bottom">Subtotal</th>
        </tr>
        <?php $no = 1 ?>
        @foreach($sale->transactions as $transaction)
            <tr valign="top">
                <td>{{$no}}</td>
                <td>{{$transaction->product->name.' - '.$transaction->desc}}</td>
                <td class="text-right">{{number_format($transaction->selling_price)}}</td>
                <td class="text-right">{{$transaction->disc}}</td>
                <td>{{$transaction->units}}</td>
                <td class="text-right">{{abs($transaction->qty)}}</td>
                <td class="text-right">{{number_format(abs($transaction->qty) * ($transaction->selling_price - $transaction->disc) * $transaction->attribute)}}</td>
            </tr>
            <?php $no++ ?>
        @endforeach
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="margin-top:35px;" width="100%">
        <tbody>
        <tr valign="top">
            <td class="border-bottom" colspan="3"></td>
        </tr>
        <tr valign="top">
            <td class="text-left" style="width: 60%">Note:</td>
            <td class="text-right" style="width: 25%">Total :</td>
            <td class="text-right">{{number_format($sale->total)}}</td>
        </tr>
        <tr valign="top">
            <td class="text-left border-bottom" rowspan="4">{{$sale->note}}</td>
            <td class="text-right">Disc :</td>
            <td class="text-right">{{number_format($sale->disc)}}</td>
        </tr>
        <tr valign="top" class="border-bottom">
            <td class="text-right">Grand Total :</td>
            <td class="text-right">{{number_format($sale->total - $sale->disc )}}</td>
        </tr>
        <tr valign="top">
            <td class="text-right {{$sale->payment_method_id != 1 ? :'border-bottom'}}">Pay :</td>
            <td class="text-right {{$sale->payment_method_id != 1 ? :'border-bottom'}} ">{{number_format($sale->cash)}}</td>
        </tr>
        @if($sale->payment_method_id == 2)
            <?php $remain = abs($sale->total - $sale->disc - abs($sale->payment->total)); ?>
            <tr valign="top">
                <td class="text-right border-bottom">Remain :</td>
                <td class="text-right border-bottom">{{number_format($remain)}}</td>
            </tr>
        @endif
        </tbody>
    </table>
    <table class="table table-bordered table-striped" style="margin-top:35px;" width="100%">
        <thead>
        <tr>
            <th>Diterima</th>
            <th>Dikirim</th>
            <th>Diperiksa</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="border-bottom" style="width: 33%;height: 3cm;padding: 2px"></td>
            <td class="border-bottom" style="width: 33%;height: 3cm"></td>
            <td class="border-bottom" style="width: 33%;height: 3cm"></td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td class="text-left" colspan="2">
                <h5>Print at {{ date('d-m-Y') }} By : {{auth()->user()->username}} | Created By
                    : {{$sale->employee->name}}</h5>
            </td>
        </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
  //  this.print();
  qz.websocket.connect().then(function () {
    return qz.printers.getDefault();
  }).then(function (printer) {
    var config = qz.configs.create(printer);
//    var data = 'hello world';
    var printData = [
      { type: 'raw', format: 'plain', data: `{{$content}}`}
    ];
    return qz.print(config, printData);
  }).catch(function (e) {
    console.error(e);
  });
</script>
</body>
</html>