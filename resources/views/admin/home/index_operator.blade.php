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
                        <div class="card card-dark-blue" style="height: 142px">
                            <div class="card-body d-flex flex-column align-item-center justify-content-center">
                                <p class="fs-30 mb-2">Scan QRCode</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4 mb-lg-0 stretch-card transparent">
                        <div class="card card-light-blue" style="height: 142px">
                            <div class="card-body d-flex flex-column align-item-center justify-content-center">
                                <p class="fs-30 mb-2">Billing</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 stretch-card transparent">
                        <div class="card card-inverse-success" style="height: 142px">
                            <div class="card-body d-flex flex-column align-item-center justify-content-center">
                                <p class="fs-30 mb-2">History</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="{{ asset('admin-page') }}/vendors/select2/select2.min.js"></script>
    @endpush
