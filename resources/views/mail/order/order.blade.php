<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width"/>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet"
    />

    <title>Order {{ $order->status == \App\Constants\AppConstant::ORDER_ACTIVE ? 'In-Review' : $order->status }}</title>

</head>
<body style="
      margin: 0;
      padding-top:20px;
      background: #f4f4f4;
    ">

<div style="
  padding: 50px 40px 46px;
  width: 800px;
  background: #fff;
  box-sizing: border-box;
  margin: 20px auto;
">
    <a href="https://prokash.io/"><img src="https://prokash.io/logo.png" height="40" alt="prokash"></a>

    <table style="width: 100%; margin-bottom: 32px; margin-top: 20px;">
        <thead>
        <tr>
            <th style="
        color: #000;
        font-family: 'Roboto', sans-serif;
        font-size: 18px;
        font-style: normal;
        font-weight: 500;
        line-height: 32px;
        text-align: left;
      ">Order Info</th>
            <th style="
        color: #000;
        font-family: 'Roboto', sans-serif;
        font-size: 18px;
        font-style: normal;
        font-weight: 500;
        line-height: 32px;
        text-align: left;
      ">Customer Info</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <p style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 22px;
        margin: 0px;
      ">Invoice No: <span style="
        font-weight: 400;
      ">{{ $order->id }}</span></p>
                <p style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 22px;
        margin: 0px;
      ">Date: <span style="
        font-weight: 400;
      ">{{ $order->created_at->format('d-M-Y') }}</span></p>
                <p style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 22px;
        margin: 0px;
      ">Payment Method: <span style="
        font-weight: 400;
      ">{{ $order->payment->payment_method ?? '---' }}</span></p>
                <p style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 22px;
        margin: 0px;
      ">TRX ID: <span style="
        font-weight: 400;
      ">{{ $order->payment->gateway_trx_id ?? '---' }}</span></p>
            </td>
            <td>
                <p style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 22px;
        margin: 0px;
      ">Name: <span style="
        font-weight: 400;
      ">{{ $order->customer->name ?? '' }}</span></p>
                <p style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 22px;
        margin: 0px;
      ">Number: <span style="
        font-weight: 400;
      ">{{ $order->customer->mobile ?? '' }}</span></p>
                <p style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 22px;
        margin: 0px;
      ">Email: <span style="
        font-weight: 400;
      ">{{ $order->customer->email }}</span></p>
                <p style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 22px;
        margin: 0px;
      ">Order Status: <span style="
        font-weight: 400;
        background: {{ $color }};
        padding: 2px 10px ;
        border-radius: 8px;
        color: #fff;
      ">{{ $order->status == \App\Constants\AppConstant::ORDER_ACTIVE ? 'In-Review' : $order->status }}</span></p>
            </td>
        </tr>
        </tbody>
    </table>


    <table style="margin-bottom: 32px; width: 100%; font-family: 'Roboto', sans-serif;  border: 1px solid black;
    border-collapse: collapse;">
        <thead>
        <tr style="text-align: left;     font-size: 14px; background: #f4f4f4;">
            <th style="border: 1px solid #ddd;
          border-collapse: collapse; padding: 5px 10px;">promotion</th>
            <th style="border: 1px solid #ddd;
          border-collapse: collapse; padding: 5px 10px;">Duration(Days)</th>
            <th style="border: 1px solid #ddd;
          border-collapse: collapse; padding: 5px 10px;">Amount(TK)</th>
        </tr>
        </thead>
        <tbody style="text-align: left;     font-size: 14px;">
        <tr>
            <td style="border: 1px solid #ddd;
        border-collapse: collapse; padding: 5px 10px;">{{ $order->promotion }}</td>
            <td style="border: 1px solid #ddd;
        border-collapse: collapse; padding: 5px 10px;">{{ $order->promotion_period }}</td>
            <td style="border: 1px solid #ddd;
        border-collapse: collapse; padding: 5px 10px;">{{ $order->amount }}</td>
        </tr>
        </tbody>
    </table>
    <p style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 22px;
        margin: 0px 0px 32px;
      ">Thank you for choosing Prokash.</p>
    <p style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 22px;
        margin: 0px;
      ">Best Regards,</p>
    <p style="
        color: #834BFF;
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        line-height: 22px;
        margin: 0px 0px 32px;
      "><a href="https://prokash.io/" style="text-decoration:none; color: #834BFF"> prokash.io</a></p>

    <p style="
      color: #333;
      font-family: 'Roboto', sans-serif;
      font-size: 12px;
      font-style: normal;
      font-weight: 500;
      line-height: 22px;
      margin: 0px 0px 32px;
    "
    >Note: This invoice is auto generated, so no need any signature on it.</p>
</body>
</html>
