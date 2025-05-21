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
                        <h4 class="card-title">Billing Operator</h4>

                        <button type="button" class="btn btn-sm btn-light" id="refresh-bank-account-btn">Refresh</button>

                        <div class="table-responsive mt-2">
                            <table class="table table-striped" id="billing-operator-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Action</th>
                                        <th>Created By</th>
                                        <th>Destination Name</th>
                                        <th>Billing Number</th>
                                        <th>Total Order</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Created At</th>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.8.1/autoNumeric.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#billing-operator-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('operator.billingHistory') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'destination_name', name: 'destination_name' },
                    { data: 'billing_number', name: 'billing_number' },
                    { data: 'total_ticket_order', name: 'total_ticket_order' },
                    { data: 'total_amount', name: 'total_amount' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' }
                ],
                autoWidth: false,
                responsive: true
            });

            // ✅ Enable manual refresh button
            $('#refresh-bank-account-btn').on('click', function() {
                table.ajax.reload();
            });

            // ✅ Auto-refresh every 1 minute
            setInterval(function() {
                table.ajax.reload();
            }, 60000);
        });
    </script>
@endpush
