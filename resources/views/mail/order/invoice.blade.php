<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice</title>

    <!-- Start Common CSS -->
    <style type="text/css">
        #outlook a {padding:0;}
        body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; font-family: Helvetica, arial, sans-serif;}
        .ExternalClass {width:100%;}
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
        .backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
        .main-temp table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; font-family: Helvetica, arial, sans-serif;}
        .main-temp table td {border-collapse: collapse;}
    </style>
    <!-- End Common CSS -->
</head>
<body>
<table width="auto" cellpadding="0" cellspacing="0" border="0" class="backgroundTable main-temp" style="background-color: #eeeded;">
    <tbody>
    <tr>
        <td>
            <table width="auto" align="center" cellpadding="15" cellspacing="0" border="0" class="devicewidth" style="background-color: #fff;">
                <tbody>
                <!-- Start header Section -->
                <tr>
                    <td style="padding-top: 30px; background-color: #f4f4f4;">
                        <table width="auto" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner" style="border-bottom: 1px solid #eeeeee; text-align: center;">
                            <tbody>
                            <tr>
                                <td style="padding-bottom: 10px;">
                                    <a href="https://prokash.io"><img src="{{ asset('/images/logo.png') }}" alt="Prokash.io" style="max-height: 60px;width: auto;" /></a>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 14px; line-height: 18px; color: #666666;">
                                    Street Address
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 14px; line-height: 18px; color: #666666;">
                                    City, Country
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 14px; line-height: 18px; color: #666666;">
                                    Phone: XXXXXXXXXXX | Email: info@prokash.io
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 14px; line-height: 18px; color: #666666; padding-bottom: 25px;">
                                    <strong>Invoice No.:</strong> #{{ $order->id }} | <strong>Date:</strong> {{ $order->created_at->format('d-M-Y') }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- End header Section -->

                <!-- Start address Section -->
                <tr>
                    <td style="padding-top: 20px;">
                        <table width="auto" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner" style="border-bottom: 1px solid #bbbbbb;">
                            <tbody>
                            <tr>
                                <td style="width: 100%; font-size: 16px; font-weight: bold; color: #666666; padding-bottom: 5px;">
                                    Customer Info
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 55%; font-size: 14px; line-height: 18px; color: #666666;">
                                    {{ $order->customer->name }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 55%; font-size: 14px; line-height: 18px; color: #666666;">
                                    {{ $order->customer->email }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 55%; font-size: 14px; line-height: 18px; color: #666666; padding-bottom: 10px;">
                                    {{ $order->customer->mobile }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- End address Section -->

                <!-- Start product Section -->
                <tr>
                    <td style="padding-top: 0;">
                        <table width="auto" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner" style="border-bottom: 1px solid #eeeeee;">
                            <tbody>
                            <tr>
                                <td style="font-size: 14px; font-weight: bold; color: #666666; padding-bottom: 5px;">
                                    {{ $order->promotion }},
                                    {{ $order->promotion_period }} days,
                                    {{ $order->promotion_objective }}
                                </td>
                                <td style="font-size: 14px; line-height: 18px; color: #757575; text-align: right; padding-bottom: 10px;">
                                    <b style="color: #666666;">BDT. {{ $order->amount }}</b>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- End product Section -->

                <!-- Start calculation Section -->
                <tr>
                    <td style="padding-top: 0;">
                        <table width="auto" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner" style="border-bottom: 1px solid #bbbbbb; margin-top: -5px;">
                            <tbody>
                            <tr>
                                <td rowspan="5" style="width: 55%;"></td>
                                <td style="font-size: 14px; line-height: 18px; color: #666666;">
                                    Total:
                                </td>
                                <td style="font-size: 14px; line-height: 18px; color: #666666; width: 130px; text-align: right;">
                                    {{ $order->amount }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- End calculation Section -->

                <!-- Start payment method Section -->
                <tr>
                    <td style="padding: 20px; background-color: #f4f4f4;">
                        <table width="auto" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                            <tbody>
                            <tr>
                                <td colspan="2" style="font-size: 16px; font-weight: bold; color: #666666; padding-bottom: 5px;">
                                    Payment Method (bKash)
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 55%; font-size: 14px; line-height: 18px; color: #666666;">
                                    ACC Name: {{ $order->payment->account_number ?? '' }}
                                </td>
                                <td style="width: 45%; font-size: 14px; line-height: 18px; color: #666666;">
                                    TRX ID: {{ $order->payment->gateway_trx_id ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 55%; font-size: 14px; line-height: 18px; color: #666666;">
                                    Amount: {{ $order->amount }}
                                </td>
                                <td style="width: 45%; font-size: 14px; line-height: 18px; color: #666666;">
                                    Status: {{ $order->payment->status ?? 'Pending' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="width: 100%; text-align: center; font-style: italic; font-size: 13px; font-weight: 600; color: #666666; padding: 15px 0; border-top: 1px solid #eeeeee;">
                                    <b style="font-size: 14px;">Note:</b> This invoice is auto generated, so no need any signature on it.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- End payment method Section -->
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
