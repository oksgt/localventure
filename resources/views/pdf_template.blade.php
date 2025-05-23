<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Multi-Page Ticket PDF</title>
    <style>
        @page {
            size: A6 portrait;
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            height: 100vh;
            background: url('data:image/png;base64,{{ $base64Image }}');
            background-repeat: repeat;
            background-size: 100px 70px;
            background-position: top left;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            /* margin: 0 0 40px 0; */
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    @foreach ($ticketOrderDetails as $detail)
        <div id="bg" class="container text-center border border-danger">
            <div class="row">
                <div style="text-align: center; padding:0">
                    <p style="font-size: 30px; margin: 10; color: #1a374d; font-weight: bold; margin-bottom: 0px">
                        {{ $title }}</p>
                    <p style="margin-top: 10px; margin-bottom: 10px;">e-Ticket #{{ $detail->ticket_code }} </p>
                </div>
                <h4 style="margin-left: 20px; color: #1a374d; margin-bottom: 0px; margin-top: 0px">Ticket
                    {{ $loop->iteration }} of {{ count($ticketOrderDetails) }}</h4>
                <hr style="border-width: 0.5px; margin-right: 20px; margin-left: 20px">

                <table style="width: 100%; margin-right: 20px; margin-left: 20px">
                    <tr>
                        <td style="width: 30%; text-align: center; ">
                            <img src="data:image/png;base64,{{ $detail->qrcode }}" style="width: 150px;">
                            <h3 style="margin-top: 5px; margin-bottom: 5px; color: #1a374d">
                                {{ ucwords($detail->day_type) }}</h3>
                        </td>
                        <td style="width: 70%; vertical-align: top; padding-left: 20px;">
                            <p style="margin-top:0px; margin-bottom:0px; color: #1a374d">Valid for: </p>
                            <p style="margin-top:0px;">{{ \Carbon\Carbon::parse($detail->date)->format('d M Y') }}</p>

                            <p style="margin-top:0px; margin-bottom:10px; color: #1a374d">1 (Person) -
                                {{ ucwords($detail->guestType->name) }}</p>
                            <p style="color: #1a374d">Price :
                                {{ 'IDR ' . number_format($detail->total_price, 2, ',', '.') }}</p>
                        </td>
                    </tr>
                </table>
                <hr style="border-width: 0.5px; margin-right: 20px; margin-left: 20px">
                <table
                    style="width: 90%; margin-right: 20px; margin-left: 20px;  background-color: #1a374d; color: white">
                    <tr>
                        <td style="font-size: 13px;">
                            Price include tax and platform fee./
                            <br>
                            Harga termasuk pajak dan biaya platform.
                        </td>
                    </tr>
                </table>

            </div>
        </div>
        <p
            style="position: fixed; bottom: 0; left: 0; font-size: 12px; margin-left: 20px; background-color: #1a374d; color: white; padding: 5px">
            This ticket generated at {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
        @if(!$loop->last)
            <div class="page-break"></div> <!-- ✅ Only add page break if NOT last item -->
        @endif

    @endforeach

</body>

</html>
