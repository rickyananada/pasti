<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Report On Proccess</title>
    <style type="text/css">
        table tr td,
        table tr th {
            font-size: 9pt;
        }
    </style>
</head>

<body>
    <center>
        <h3>Report {{$st}}</h3>
    </center>
    <table class='table table-bordered'>
        <thead>
            <tr>
                <th>No</th>
                <th>Customer</th>
                <th>Address</th>
                <th>Courier</th>
                <th>Item</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
            $i=1;
            $totalharga = 0;
            @endphp
            @foreach ($order as $order)
            @php
            $totalharga += $order->total;
            @endphp
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{$order->name}}</td>
                <td>{{$order->address}}</td>
                <td>{{$order->ekspedisi}}</td>
                <td>
                    @foreach($order->order_detail as $orders)
                    {{$orders->titles}} <br>
                    @endforeach
                </td>
                <td>{{$order->st}}</td>
                <td>{{number_format($order->total)}}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="6">Total Harga</td>
                <td>{{number_format($totalharga)}}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>