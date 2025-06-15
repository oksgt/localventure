<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Konfirmasi Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
            padding: 30px 15px;
        }

        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .btn-custom {
            margin-right: 10px;
            margin-top: 10px;
        }

        .footer {
            font-size: 12px;
            color: #6c757d;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <h4 class="mb-3">✅ Pembayaran Anda Telah Dikonfirmasi</h4>
        <p>Halo <strong>{{ $userName }}</strong>,</p>
        <p>
            Kami telah menerima pembayaran Anda dengan nomor tagihan
            <strong>{{ $billingNumber }}</strong>.
        </p>
        <p>
            Tiket Anda sudah tersedia dan dapat langsung diunduh melalui tautan berikut:
        </p>
        <a href="{{ $invoiceUrl }}" class="btn btn-primary btn-sm btn-custom">📄 Download Invoice</a>
        <a href="{{ $ticketDownloadUrl }}" class="btn btn-success btn-sm btn-custom">🎟️ Unduh Tiket</a>

        <p class="mt-4">Jika tombol di atas tidak berfungsi, silakan klik tautan berikut:</p>
        <ul class="list-unstyled">
            <li><strong>Invoice:</strong> <a href="{{ $invoiceUrl }}">{{ $invoiceUrl }}</a></li>
            <li><strong>Tiket:</strong> <a href="{{ $ticketDownloadUrl }}">{{ $ticketDownloadUrl }}</a></li>
        </ul>

        <p>Terima kasih telah melakukan pemesanan. Have fun!</p>
        <div class="footer">
            &copy; {{ date('Y') }} @ localVenture. Semua hak dilindungi.
        </div>
    </div>
</body>

</html>
