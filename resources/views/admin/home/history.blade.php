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

            <div class="col-12">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" id="search-input" class="form-control" placeholder="Invoice Number"
                            value="{{ request('param') }}"> <!-- ✅ Keeps previous search value -->
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-primary" id="search-button" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div id="transaction-container" class="w-100">
                @foreach ($transactions as $item)
                    @php
                        $visitDate = \Carbon\Carbon::parse($item->visit_date);
                        $dayType = $visitDate->isWeekend() ? 'weekend' : 'weekday';
                    @endphp
                    <div class="col-md-12 stretch-card grid-margin grid-margin-md-0">
                        <div class="card data-icon-card-primary">
                            <div class="card-body">
                                <p class="card-title text-white">{{ $item->destination->name }}</p>
                                <div class="row">
                                    <div class="col-9 text-white">
                                        <h3>Rp. {{ number_format($item->total_price, 0, ',', '.') }}</h3>
                                        <p class="text-white font-weight-500 mb-0">Visit:
                                            {{ \Carbon\Carbon::parse($item->visit_date)->format('d/m/Y') }}</p>
                                        <p class="text-white font-weight-500 mb-0">{{ ucwords($dayType) }} -
                                            {{ $item->total_visitor }} People</p>
                                        <span class="badge badge-{{ ($item->payment_status == 'paid') ? 'success' : 'warning' }} mt-2">{{ ucwords($item->payment_status) }}</span>
                                        <small>{{ $item->billing_number }}</small>
                                    </div>
                                    <div class="col-2 background-icon">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mt-2 ">
                                        <div class="btn-group w-100" role="group" aria-label="Basic example">
                                            <a type="button" class="btn btn-rounded btn-sm btn-dark" title="Detail"
                                                href="{{ url('/admin/online-transaction/scan/' . $item->billing_number) }}">
                                                <i class="fa fa-file-text"></i>
                                            </a>
                                            <button type="button" class="btn btn-rounded btn-sm btn-dark" title="Print">
                                                <i class="fa fa-print"></i>
                                            </button>
                                            <button type="button" class="btn btn-rounded btn-sm btn-danger btn-delete"
                                                title="Delete" data-id="{{ $item->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <small>Created at {{ $item->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <div class="btn-group w-100" role="group" aria-label="Basic example">
                    <!-- First Page -->
                    <a href="{{ $transactions->url(1) }}"
                        class="btn btn-primary {{ $transactions->currentPage() == 1 ? 'disabled' : '' }}">
                        <i class="fa fa-angle-double-left"></i>
                    </a>

                    <!-- Previous Page -->
                    <a href="{{ $transactions->previousPageUrl() }}"
                        class="btn btn-primary {{ $transactions->onFirstPage() ? 'disabled' : '' }}">
                        <i class="fa fa-angle-left"></i>
                    </a>

                    <!-- Current Page -->
                    <button type="button" class="btn btn-inverse-primary">
                        Page {{ $transactions->currentPage() }} of {{ $transactions->lastPage() }}
                    </button>

                    <!-- Next Page -->
                    <a href="{{ $transactions->nextPageUrl() }}"
                        class="btn btn-primary {{ $transactions->currentPage() == $transactions->lastPage() ? 'disabled' : '' }}">
                        <i class="fa fa-angle-right"></i>
                    </a>

                    <!-- Last Page -->
                    <a href="{{ $transactions->url($transactions->lastPage()) }}"
                        class="btn btn-primary {{ $transactions->currentPage() == $transactions->lastPage() ? 'disabled' : '' }}">
                        <i class="fa fa-angle-double-right"></i>
                    </a>
                </div>

            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="{{ asset('admin-page') }}/vendors/select2/select2.min.js"></script>
        <script src="{{ asset('landing-page') }}/js/instascan_.min.js"></script>
        <script>
            $(document).ready(function() {
                var scanner = new Instascan.Scanner({
                    video: document.getElementById('qr-video')
                });

                scanner.addListener('scan', function(content) {
                    window.location.href = "{{ url('/admin/online-transaction/scan/') }}/" +
                        encodeURIComponent(
                            content);
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

                $('#search-button').on('click', function() {
                    var billingNumber = $('#search-input').val();
                    var newUrl = "{{ route('history') }}?param=" + encodeURIComponent(billingNumber); // ✅ Updates URL

                    window.location.href = newUrl; // ✅ Redirects to the updated URL (triggers full page reload)
                });
            });
        </script>
        <script>
            $(document).on('click', '.btn-delete', function() {
                var transactionId = $(this).data('id'); // ✅ Get transaction ID

                Swal.fire({
                    text: "Are you sure you want to delete this transaction?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('transaction.delete', ':id') }}".replace(':id',
                                transactionId),
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire({
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    location.reload(); // ✅ Refresh after deletion
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    text: "Error deleting transaction!",
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
