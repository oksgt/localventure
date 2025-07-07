@extends('admin.layout.master')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">Selamat datang, {{ Auth::user()->name }}</h3>
                        <h5 class="text-muted">( {{ Auth::user()->role->name }} )</h5>
                    </div>
                    <div class="col-12 col-xl-4">
                        <div class="justify-content-end d-flex">
                            <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                <button class="btn btn-sm btn-light bg-white" type="button" id="dropdownMenuDate2"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="ti-calendar"></i> Today
                                    ({{ \Carbon\Carbon::now()->locale('en')->isoFormat('DD MMM YYYY') }})
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($destinations)
            <div class="row">
                <div class="col-md-12 grid-margin transparent">
                    <div class="row">

                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <div class="card card-inverse-primary" style="height: 142px; cursor: pointer;"
                                data-toggle="modal" data-target="#checkInModal">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <p class="fs-30 mb-2">Scan Ticket</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <a href="{{ route('ticket-purchase.index') }}" class="card card-tale text-white"
                                style="height: 142px; text-decoration: none; color: inherit;">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <p class="fs-30 mb-2">Ticket Purchase</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <div class="card card-dark-blue" style="height: 142px; cursor: pointer;" data-toggle="modal"
                                data-target="#qrModal">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <p class="fs-30 mb-2">Scan Invoice</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <a href="{{ route('operator-billing') }}" class="card card-light-blue"
                                style="height: 142px; text-decoration: none; color: inherit;">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <p class="fs-30 mb-2 text-white">Billing</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <a href="{{ route('history') }}" class="card card-inverse-success"
                                style="height: 142px; text-decoration: none; color: inherit;">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <p class="fs-30 mb-2 text-success">History</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Oups!</h4>
                <p>It looks like you haven't mapped to any destinations yet</p>
                <hr>
                <p class="mb-0">Please contact to your administrator <strong>{{ Auth::user()->parent->name }}</strong></p>
            </div>
        @endif


        <div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg h-100" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="qrModalLabel">Scan Invoice</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center p-0">
                        <video id="qr-video" style="width: 100%;"></video>
                        <p class="mt-3"><strong>Scanned Code:</strong> <span id="qr-result">None</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="checkInModal" tabindex="-1" role="dialog" aria-labelledby="checkInModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg h-100" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="checkInModalLabel">Check In - Scan QR Code</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center p-0">
                        <video id="checkin-video" style="width: 100%;"></video>
                        <p class="mt-3"><strong>Scanned Ticket Code:</strong> <span id="qr-result">None</span></p>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="{{ asset('admin-page') }}/vendors/select2/select2.min.js"></script>
        <script src="{{ asset('landing-page') }}/js/instascan_.min.js"></script>
        <script>
            function playBeep() {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();

                oscillator.type = "sine"; // ✅ Produces a clean beep tone
                oscillator.frequency.setValueAtTime(1000, audioCtx.currentTime); // ✅ Set frequency (1000 Hz)
                gainNode.gain.setValueAtTime(0.5, audioCtx.currentTime); // ✅ Adjust volume (0.5 for moderate)

                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);

                oscillator.start();
                setTimeout(() => {
                    oscillator.stop();
                }, 200); // ✅ Beep duration (200ms)
            }



            $(document).ready(function() {
                var scanner = new Instascan.Scanner({
                    video: document.getElementById('qr-video')
                });
                var checkInscanner = new Instascan.Scanner({
                    video: document.getElementById('checkin-video')
                });

                scanner.addListener('scan', function(content) {
                    window.location.href = "{{ url('/admin/online-transaction/scan/') }}/" + encodeURIComponent(
                        content);
                });

                checkInscanner.addListener('scan', function(content) {
                    var ticketCode = $(this).data('ticket-code');

                    $.ajax({
                        url: "{{ route('ticket.updateCheckIn') }}",
                        type: "POST",
                        data: {
                            ticket_code: content,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            console.log('Ticket Checked In');
                            playBeep();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: "Error!",
                                text: xhr.responseJSON.message,
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        }
                    });

                });

                $('#qrModal').on('shown.bs.modal', function() {
                    Instascan.Camera.getCameras().then(function(cameras) {
                        if (cameras.length > 0) {
                            scanner.start(cameras[1]);
                        } else {
                            console.error("No cameras found.");
                        }
                    }).catch(function(e) {
                        console.error(e);
                    });
                });

                $('#qrModal').on('hidden.bs.modal', function() {
                    scanner.stop(); // ✅ Stop scanning when modal closes
                });

                $('#checkInModal').on('shown.bs.modal', function() {
                    Instascan.Camera.getCameras().then(function(cameras) {
                        if (cameras.length > 0) {
                            checkInscanner.start(cameras[0]);
                        } else {
                            console.error("No cameras found.");
                        }
                    }).catch(function(e) {
                        console.error(e);
                    });
                });

                $('#checkInModal').on('hidden.bs.modal', function() {
                    checkInscanner.stop(); // ✅ Stop scanning when modal closes
                });
            });
        </script>
    @endpush
