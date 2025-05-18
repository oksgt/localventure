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

        <div class="row">
            <div class="col-md-12 grid-margin transparent">
                <div class="row">
                    <div class="col-md-3 mb-4 stretch-card transparent">
                        <a href="{{ route('ticket-purchase.index') }}" class="card card-tale text-white"
                            style="height: 142px; text-decoration: none; color: inherit;">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                <p class="fs-30 mb-2">Ticket Purchase</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-4 stretch-card transparent">
                        <div class="card card-dark-blue" style="height: 142px; cursor: pointer;" data-toggle="modal" data-target="#qrModal">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                <p class="fs-30 mb-2">Scan QRCode</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4 mb-lg-0 stretch-card transparent">
                        <div class="card card-light-blue" style="height: 142px">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                <p class="fs-30 mb-2">Billing</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 stretch-card transparent">
                        <div class="card card-inverse-success" style="height: 142px">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                <p class="fs-30 mb-2">History</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                </div>
            </div>
        </div>
        <div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg h-100" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="qrModalLabel">Scan QR Code</h5>
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

    @endsection

    @push('scripts')
        <script src="{{ asset('admin-page') }}/vendors/select2/select2.min.js"></script>
        <script src="{{ asset('landing-page') }}/js/instascan_.min.js"></script>
        <script>
            $(document).ready(function() {
                var scanner = new Instascan.Scanner({ video: document.getElementById('qr-video') });

                scanner.addListener('scan', function(content) {
                    console.log(content);
                });

                $('#qrModal').on('shown.bs.modal', function() {
                    Instascan.Camera.getCameras().then(function(cameras) {
                        if (cameras.length > 0) {
                            scanner.start(cameras[0]);
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
            });
        </script>
    @endpush
