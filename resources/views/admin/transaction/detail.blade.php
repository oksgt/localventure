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
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6 ">
                                <p class="card-title">Detail Transaction : {{ $transaction->billing_number }}</p>
                                <div class="table-responsive p-0">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td style="width: 50px"><strong>Destination</strong></td>
                                                <td style="width: 10px">:</td>
                                                <td>{{ $transaction->destination->name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Visit Date</strong></td>
                                                <td>:</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->visit_date)->format('d F Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Name</strong></td>
                                                <td>:</td>
                                                <td>{{ $transaction->visitor_name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Address</strong></td>
                                                <td>:</td>
                                                <td>{{ $transaction->visitor_address }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Phone</strong></td>
                                                <td>:</td>
                                                <td>{{ $transaction->visitor_phone }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email</strong></td>
                                                <td>:</td>
                                                <td>{{ $transaction->visitor_email }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Notes</strong></td>
                                                <td>:</td>
                                                <td>{{ $transaction->visitor_origin_description }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Order At</strong></td>
                                                <td>:</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d F Y H:i:s') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6 grid-margin transparent">
                                <div class="row">
                                    <div class="col-md-6 mb-4 stretch-card transparent">
                                        <div class="card card-tale">
                                            <div class="card-body">
                                                <p class="mb-4">Total Visitor</p>
                                                <p class="fs-30 mb-2">{{ $transaction->total_visitor }}</p>
                                                <p>People</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4 stretch-card transparent">
                                        <div class="card card-dark-blue">
                                            <div class="card-body">
                                                <p class="mb-4">Total Transaction</p>
                                                <p class="fs-30 mb-2">{{ 'Rp ' . number_format($transaction->total_price, 2, ',', '.') }}</p>
                                                <p>{{ \Carbon\Carbon::parse($transaction->visit_date)->isWeekend() ? 'Weekend' : 'Weekday' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 stretch-card transparent">
                                        <div class="card card-inverse-info">
                                            <div class="card-body">
                                                <p class="mb-4">Payment Channel</p>
                                                <p class="fs-30 mb-2">{{ $transaction->paymentType->payment_type_name }}</p>
                                                @if ($transaction->payment_type_id == 3)
                                                    <p>{{ $transaction->bank_name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                                        <div class="card card-inverse-warning" style="cursor: pointer">
                                            <div class="card-body">
                                                <p class="mb-4">Payment Status</p>
                                                <p class="fs-30 mb-2">{{ ucwords($transaction->payment_status) }}</p>
                                                <p>Click to check </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-4">
                                <div class="table-responsive">
                                    <p class="card-title">Ticket List</p>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Ticket Code</th>
                                                <th>Guest Type</th>
                                                <th>Day Type</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($transactionDetail as $item)
                                                <tr></tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->ticket_code }}</td>
                                                <td>{{ $item->guestType->name }}</td>
                                                <td>{{ $item->day_type }}</td>
                                                <td>{{ 'Rp ' . number_format($item->total_price, 2, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
@endpush
