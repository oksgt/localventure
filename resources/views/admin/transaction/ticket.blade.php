<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ticket PDF Generator</title>
    <style>
        /* Ticket styles */
        .ticket-wrap {
            text-align: center;
        }

        .ticket {
            display: block;
            width: 100%;
            margin: 0 auto;
            font-family: "Bahnschrift", sans-serif;
            background: #fff;
        }

        .ticket__header {
            padding: 1.5em;
            background: #9facbc;
            color: white;
            text-align: center;
        }

        .ticket__co-name {
            font-size: 2.5em;
            font-weight: 500;
        }

        .ticket__co-subname {
            font-weight: 700;
        }

        .ticket__body {
            padding: 2rem 1.25em 1.25em;
            text-align: center;
        }

        .ticket__route {
            font-size: 2em;
            font-weight: 300;
        }

        .ticket__description {
            margin-top: .5em;
            font-size: 1.125em;
        }

        .ticket__timing {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
            padding: 1rem 0;
            border-top: 2px solid #9facbc;
            border-bottom: 2px solid #9facbc;
        }

        .ticket__timing p {
            margin: 0 1rem 0 0;
            padding-right: 1rem;
            border-right: 2px solid #9facbc;
        }

        .ticket__timing p:last-child {
            margin: 0;
            padding: 0;
            border-right: 0;
        }

        .ticket__admit {
            margin-top: 2rem;
            font-size: 2.5em;
            font-weight: 700;
        }

        #qrcode {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 1.25em;
        }
    </style>
</head>

<body>
    <div id="ticket-content" style="width: 400px;">
        <div class="ticket-wrap">
            <div class="ticket">
                <div class="ticket__header">
                    <div class="ticket__co">
                        <span class="ticket__co-name">LocalVenture</span><br>
                        <span class="ticket__co-subname">e-Ticketing</span>
                    </div>
                </div>
                <div class="ticket__body">
                    <p class="ticket__route">{{ $ticketOrder->destination->name }}</p>
                    <p class="ticket__description">{{ ucwords($transactionDetail->day_type) }} - {{ $transactionDetail->qty }} Person</p>
                    <div class="ticket__timing">
                        <p>
                            <span>Date</span>
                            <span>{{ \Carbon\Carbon::parse($transactionDetail->visit_date)->format('d F Y') }}</span>
                        </p>
                        <p>
                            <span>Guest</span>
                            <span>Anak-anak</span>
                        </p>
                    </div>
                    <p>This ticket is valid for one person</p>
                    <div id="qrcode"></div>
                    <p class="ticket__admit">#{{ $transactionDetail->ticket_code }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Load Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        function generateQRCode() {
            let admitCode = document.querySelector(".ticket__admit").textContent.trim();
            let qrcodeContainer = document.querySelector("#qrcode");
            qrcodeContainer.innerHTML = ""; // Clear previous QR code

            new QRCode(qrcodeContainer, {
                text: admitCode, // Generates QR from ticket__admit value
                width: 200,
                height: 200,
                correctLevel: QRCode.CorrectLevel.H
            });
        }

        async function generatePDF() {
            const { jsPDF } = window.jspdf;
            let doc = new jsPDF({
                orientation: "p", // "p" = portrait, "l" = landscape
                unit: "mm", // Measurement unit ("mm", "cm", "in", "px")
                format: [148, 250]
            });

            html2canvas(document.querySelector("#ticket-content"), {
                scale: 2,
                useCORS: true,
                allowTaint: true,
                backgroundColor: null
            }).then(canvas => {
                let imgData = canvas.toDataURL("image/png");
                let imgWidth = 130;
                let imgHeight = (canvas.height * imgWidth) / canvas.width;

                doc.addImage(imgData, "PNG", 10, 10, imgWidth, imgHeight);
                doc.save("ticket.pdf");
            });
        }

        window.onload = function () {
            generateQRCode(); // Generate QR code first
            setTimeout(generatePDF, 200); // Delay slightly to ensure QR code is rendered
        };
    </script>
</body>

</html>
