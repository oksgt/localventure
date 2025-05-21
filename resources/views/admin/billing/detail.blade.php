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
                                <p class="card-title">Detail Billing Operator : {{ $operatorTransaction->billing_number }}
                                </p>
                                <div class="table-responsive p-0">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td style="width: 50px"><strong>Destination</strong></td>
                                                <td style="width: 10px">:</td>
                                                <td>{{ $operatorTransaction->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50px"><strong>Total Order</strong></td>
                                                <td style="width: 10px">:</td>
                                                <td>{{ $operatorTransaction->total_ticket_order }}</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50px"><strong>Total Amount</strong></td>
                                                <td style="width: 10px">:</td>
                                                <td>Rp. {{ number_format($operatorTransaction->total_amount) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50px"><strong>Billing Date</strong></td>
                                                <td style="width: 10px">:</td>
                                                <td>Rp.
                                                    {{ Carbon\Carbon::parse($operatorTransaction->created_at)->format('d F Y H:i:s') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50px"><strong>Status</strong></td>
                                                <td style="width: 10px">:</td>
                                                <td>
                                                    @if ($operatorTransaction->transfer_approval == 0)
                                                        <button class="btn btn-sm btn-warning" id="showPayment"
                                                            data-billing="{{ $operatorTransaction->billing_number }}"><i
                                                                class="fa fa-edit "></i> Unpaid</button>
                                                    @elseif ($operatorTransaction->transfer_approval == 1)
                                                        <button class="btn btn-sm btn-success"><i class="fa fa-check-circle "></i>
                                                            Paid</button> - Confirmed at {{ Carbon\Carbon::parse($operatorTransaction->transfer_approval_date)->format('d F Y H:i:s') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6 grid-margin transparent">
                                <div class="row d-flex align-items-center justify-content-center">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <p class="card-title">Ticket Order</p>
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Invoice</th>
                                                        <th>Payment Date</th>
                                                        <th>Total Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($OperatorTransactionDetail as $item)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $item->invoice }}</td>
                                                            <td>{{ Carbon\Carbon::parse($item->order_date)->format('d F Y H:i:S') }}
                                                            </td>
                                                            <td>Rp. {{ number_format($item->amount) }}</td>
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
        </div>

    </div>

    <!-- ✅ Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Payment Approval</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this payment?</p>
                    <form id="paymentForm">
                        @csrf
                        <input type="hidden" id="billingNumber" name="billing_number">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="confirmPayment">Approve Payment</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            // ✅ Show modal when clicking the card
            $('#showPayment').on('click', function() {
                var billingNumber = $(this).data('billing');
                $('#billingNumber').val(billingNumber);
                $('#paymentModal').modal('show'); // Open modal
            });

            // ✅ Handle payment confirmation AJAX request
            $('#confirmPayment').on('click', function() {
                var billingNumber = $('#billingNumber').val();

                Swal.fire({
                    title: "Confirm Payment?",
                    text: "This action will mark the billing as PAID.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Approve",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('operator.approvePayment') }}",
                            type: "POST",
                            data: {
                                billing_number: billingNumber,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    location
                                .reload(); // ✅ Reload page after approval
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Failed to approve payment!",
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
