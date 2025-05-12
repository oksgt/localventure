<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Invoice</title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="data:image/png;base64,{{ $base64Image }}" style="width: 200px">
                            </td>

                            <td>
                                Invoice #: {{ $invoice_number }}<br />
                                Created: {{ $created_date }}<br />
                                @if ($payment_status == 'paid')
                                    {{ $purchasing_type }} - <span style="background-color: green; color: white; padding: 3px 10px 3px 10px">{{ $payment_status }}</span>
                                @else
                                    {{ $purchasing_type }} - <span style="background-color: red; color: white; padding: 3px 10px 3px 10px">{{ $payment_status }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                PT LocalVenture Tourism<br />
                                Jl. Utama, No. 1. Kota Besar<br />
                                081-123-4567<br />
                            </td>

                            <td>
                                Oka Sigit<br />
                                081-123-4567<br />
                                oka.sigit@gmail.com
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Item</td>

                <td>Price</td>
            </tr>

            @foreach ($items as $item)
                <tr class="item">
                    <td>Ticket {{ $item->guest_type }} <i>({{ $item->day_type }}) x {{ $item->total_qty }} </i> </td>

                    <td>Rp {{ number_format($item->total_price, 2, ',', '.') }}</td>
                </tr>
            @endforeach



            <tr class="total">
                <td style="padding-bottom: 20px;"></td> <!-- ✅ Adds gap -->
                <td style="padding-bottom: 20px;">Total: Rp {{ $total_price }}</td>
            </tr>



            @if ($payment_type == 'QRIS')
                <tr class="heading">
                    <td>Payment Method</td> <!-- ✅ Adds gap -->
                    <td>QRIS #</td>
                </tr>

                <tr class="details">
                    <td colspan="2" style="text-align: center;">
                        <img src="data:image/png;base64,{{ $base64ImageQRIS }}" style="width: 200px" />
                    </td>
                </tr>
            @else
                <tr class="heading">
                    <td>Payment Method</td> <!-- ✅ Adds gap -->
                    <td>Bank Transfer #</td>
                </tr>

                <tr class="details">
                    <td>Bank BCA 1234567890 a.n. PT. Wisata Kalianget</td>

                    <td>$385.00</td>
                </tr>
            @endif


        </table>
    </div>
</body>

</html>
