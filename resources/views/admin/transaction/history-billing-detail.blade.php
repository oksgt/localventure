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
                            <div class="col-12 col-md-6 mb-3">
                                <p class="card-title">Detail Billing : {{ $operatorTransaction->billing_number }}</p>
                                <div class="table-responsive p-0">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>Destination</td>
                                            <td>:</td>
                                            <td>{{ $operatorTransaction->destination_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Amount</td>
                                            <td>:</td>
                                            <td>Rp. {{ number_format($operatorTransaction->total_amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Total Order</td>
                                            <td>:</td>
                                            <td>{{ number_format($operatorTransaction->total_ticket_order, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Created at</td>
                                            <td>:</td>
                                            <td>{{ \Carbon\Carbon::parse($operatorTransaction->created_at)->format('d F Y H:i:s') }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                @if ($operatorTransaction->transfer_approval == 0)
                                    <div class="alert alert-warning">
                                        <i class="fa fa-times-circle"></i> Unpaid
                                    </div>
                                @else
                                    <div class="alert alert-success">
                                        <i class="fa fa-check-circle"></i> Paid
                                    </div>
                                @endif
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <p class="card-title">Invoice List </p>
                                <div class="table-responsive p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Invoice Number</th>
                                                <th>Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($transactionDetails->count() > 0)
                                                @foreach ($transactionDetails as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->ticket_order_billing_number }}</td>
                                                        <td>{{ 'Rp ' . number_format($item->amount, 2, ',', '.') }}
                                                        </td>
                                                        <td>
                                                            <a href="{{ url('admin/online-transaction/scan', $item->ticket_order_billing_number) }}"
                                                                class="btn btn-sm btn-primary"><i
                                                                    class="fa fa-file-alt"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
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
