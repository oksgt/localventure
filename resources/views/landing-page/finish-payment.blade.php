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
                        <td>0703010000000019</td>
                    </tr>
                    <tr>
                        <td><strong>Total Tagihan</strong></td>
                        <td>Rp. 30,000,-</td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Pengunjung</strong></td>
                        <td>3 Pengunjung</td>
                    </tr>
                    <tr>
                        <td><strong>Keterangan</strong></td>
                        <td>Tiket Wisata Kalianget Pejabat Gemes, Semarang Barat, Ngamplak Simongan</td>
                    </tr>
                    <tr>
                        <td><strong>Status Bayar</strong></td>
                        <td style="background: green; color: white;">Belum Lunas</td>
                    </tr>
                </table>

                <div class="board-wrapper" style="margin-top: 20px;">
                    <div class="board-inner">
                        <div class="board-item">
                            Silahkan Transfer ke Rekening di bawah ini:
                            <h4>Bank BCA 1234567890</h4>
                            <h4>a.n. PT. Wisata Kalianget</h4>
                        </div>
                        <p>* Note: Download Invoice atau Screenshot halaman ini</p>
                    </div>

                    <button id="downloadPdf" style="margin-top: 20px">Download <i class="zmdi zmdi-download"></i></button>
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
        document.getElementById("downloadPdf").addEventListener("click", function () {
            const { jsPDF } = window.jspdf;

            html2canvas(document.body, { scale: 2 }).then(canvas => {
                const doc = new jsPDF("p", "mm", "a4");
                const imgData = canvas.toDataURL("image/png");

                doc.addImage(imgData, "PNG", 10, 10, 190, 0);
                doc.save("Full-Page.pdf");
            });
        });
    </script>
</body>

</html>
