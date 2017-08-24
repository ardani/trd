<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Invoice</title>
    <script src="{{asset('js/rsvp.min.js')}}"></script>
    <script src="{{asset('js/sha256.min.js')}}"></script>
    <script src="{{asset('js/qz-tray.js')}}"></script>
</head>
<body>
<pre>{{$content}}</pre>
<script type="text/javascript">
  qz.websocket.connect().then(function () {
    return qz.printers.getDefault();
  }).then(function (printer) {
    var config = qz.configs.create(printer);
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