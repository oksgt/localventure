<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Booking Ticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="colorlib.com">

    <!-- LINEARICONS -->
    <link rel="stylesheet" href="{{ asset('booking') }}/fonts/linearicons/style.css">

    <!-- MATERIAL DESIGN ICONIC FONT -->
    <link rel="stylesheet"
        href="{{ asset('booking') }}/fonts/material-design-iconic-font/css/material-design-iconic-font.css">

    <!-- DATE-PICKER -->
    <link rel="stylesheet" href="{{ asset('booking') }}/vendor/date-picker/css/datepicker.min.css">

    <!-- STYLE CSS -->
    <link rel="stylesheet" href="{{ asset('booking') }}/css/style.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
</head>

<body>
    <div class="wrapper">
        <div class="inner" id="capture">
            <div class="image-holder">
                <img id="selectedImage" src="{{ $selectedImage }}" alt="">
                <h3>Payment Information</h3>
            </div>

            <div class="payment-information">
                <table style="width: 100%; border: 1px solid #ccc; border-radius: 5px; padding: 20px;">
                    <tr>
                        <td><strong>ID Billing</strong></td>
                        <td>{{ $transaction->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Tagihan</strong></td>
                        <td>Rp. {{ number_format($transaction->total_price, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Pengunjung</strong></td>
                        <td>{{ $transaction->total_visitor }} Pengunjung</td>
                    </tr>
                    <tr>
                        <td><strong>Keterangan</strong></td>
                        <td>{{ $transaction->notes }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status Bayar</strong></td>
                        @if ($transaction->payment_status == 'paid')
                            <td style="background: green; color: white;">Lunas</td>
                        @else
                            <td style="background: red; color: white;">Belum Lunas</td>
                        @endif

                    </tr>
                </table>

                <div class="board-wrapper" style="margin-top: 20px;">
                    @if ($transaction['payment_type_id'] == 3)
                        <div class="board-inner">
                            <div class="board-item">
                                Silahkan Transfer ke Rekening di bawah ini:
                                <h4>{{ $transaction->bank->bank_name }} - {{ $transaction->bank->account_number }}</h4>
                                <h4>a.n. {{ $transaction->bank->account_name }}</h4>
                            </div>
                            <p>* Note: Download Invoice atau Screenshot halaman ini</p>
                        </div>
                    @elseif ($transaction['payment_type_id'] == 1)
                        <div class="board-inner">
                            <div class="board-item">
                                Silahkan scan code QRIS
                                <br>
                                <div style="text-align: center">
                                    <img src="{{ asset('storage/' . $transaction['qris']->payment_image) }}" alt="qris"
                                        style="width: 200px; ">
                                </div>
                            </div>
                            <p>* Note: Download Invoice atau Screenshot halaman ini</p>
                        </div>
                    @endif

                    <button id="downloadPdf" data-id="{{ $transaction['id'] }}" style="margin-top: 20px; width: 200px;">Download Invoice <i
                            class="zmdi zmdi-download"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('booking') }}/js/jquery-3.3.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <!-- JQUERY STEP -->
    <script src="{{ asset('booking') }}/js/jquery.steps.js"></script>

    <!-- HTML2CANVAS & JSPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script src="{{ asset('booking') }}/js/main.js"></script>

    <script>
        document.getElementById("downloadPdf").addEventListener("click", function() {
            let invoiceId = this.getAttribute("data-id");
            let url = "{{ route('invoice', ':id') }}".replace(':id', invoiceId); // ✅ Dynamically set URL

            window.location.href = url; // ✅ Redirects to download URL
        });
    </script>
</body>

</html>
