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
                                <button class="btn btn-sm btn-light bg-white" type="button"
                                    id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="ti-calendar"></i> Today ({{ \Carbon\Carbon::now()->locale('en')->isoFormat('DD MMM YYYY') }})
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
                        <h4 class="card-title">Online Ticket Order</h4>

                        <button type="button" class="btn btn-sm btn-light" id="refresh-bank-account-btn">Refresh</button>

                        <div class="table-responsive mt-2">
                            <table class="table table-striped" id="transaction-online-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Action</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Visit Date</th>
                                        <th>Inv. Number</th>
                                        <th>Visitor Name</th>
                                        <th>Total Visitor</th>
                                        <th>Dest. Name</th>
                                        <th>Keterangan</th>
                                        <th>Amount</th>
                                        <th>Order Date</th>
                                    </tr>
                                </thead>
                            </table>
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

    <script>
        $(document).ready(function () {
            let table = $('#transaction-online-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.transaction.data') }}",
                columns: [
                    {
                        data: null,
                        name: 'row_number',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Auto-generate row number
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                    { data: 'payment_name', name: 'payment_name' },
                    { data: 'payment_status', name: 'payment_status' },
                    { data: 'visit_date', name: 'visit_date' },
                    { data: 'billing_number', name: 'billing_number' },
                    { data: 'visitor_name', name: 'visitor_name' },
                    { data: 'total_visitor', name: 'total_visitor' },
                    { data: 'destination_name', name: 'destination_name' },
                    { data: 'notes', name: 'notes' },
                    { data: 'total_price', name: 'total_price' },
                    { data: 'created_at', name: 'created_at' },
                ]
            });

            // ✅ Manual Refresh Button
            $('#refresh-bank-account-btn').on('click', function () {
                table.ajax.reload(null, false); // ✅ Refresh without resetting pagination
            });

            // ✅ Auto-Refresh every 1 minute (60000ms)
            setInterval(function () {
                table.ajax.reload(null, false); // ✅ Keeps pagination position
            }, 60000);
        });
    </script>
@endpush

