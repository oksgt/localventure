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
                                <p class="card-title">Detail Transaction : {{ $transaction->billing_number }}</p>

                                <a href="{{ url('/download/ticket/baru/'.$transaction->id) }}" class="btn btn-block btn-primary mb-3">Download Ticket</a>
                                <a href="{{ url('/download-invoice/'.$transaction->id) }}" class="btn btn-border btn-block btn-outline-primary mb-3">Download Invoice</a>


                                <div class="card card-tale mb-3">
                                    <div class="card-body">
                                        <p class="mb-4">Total Visitor</p>
                                        <p class="fs-30 mb-2">{{ $transaction->total_visitor }}</p>
                                        <p>People</p>
                                    </div>
                                </div>

                                <div class="card card-dark-blue mb-3">
                                    <div class="card-body">
                                        <p class="mb-4">Total Transaction</p>
                                        <p class="fs-30 mb-2">
                                            {{ 'Rp ' . number_format($transaction->total_price, 2, ',', '.') }}</p>
                                        <p>{{ \Carbon\Carbon::parse($transaction->visit_date)->isWeekend() ? 'Weekend' : 'Weekday' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="card card-inverse-info mb-3">
                                    <div class="card-body">
                                        <p class="mb-4">Payment Channel</p>
                                        <p class="fs-30 mb-2">{{ $transaction->paymentType->payment_type_name }}
                                        </p>
                                        @if ($transaction->payment_type_id == 3)
                                            <p>{{ $transaction->bank_name }}</p>
                                        @elseif ($transaction->payment_type_id == 4)
                                            <p>Onsite</p>
                                        @endif
                                    </div>
                                </div>
                                @if ($transaction->payment_status == 'pending')
                                    <div class="card card-inverse-warning mb-3" style="cursor: pointer"
                                        data-id="{{ $transaction->billing_number }}">
                                        <div class="card-body">
                                            <p class="mb-4">Payment Status</p>
                                            <p class="fs-30 mb-2">{{ ucwords($transaction->payment_status) }}</p>
                                        </div>
                                    </div>
                                @elseif ($transaction->payment_status == 'paid')
                                    <div class="card card-inverse-success mb-3" style="cursor: pointer"
                                        data-id="{{ $transaction->billing_number }}">
                                        <div class="card-body">
                                            <p class="mb-4">Payment Status</p>
                                            <p class="fs-30 mb-2">{{ ucwords($transaction->payment_status) }}</p>
                                        </div>
                                    </div>

                                @elseif ($transaction->payment_status == 'received')
                                    <div class="card card-inverse-info mb-3" style="cursor: pointer"
                                        data-id="{{ $transaction->billing_number }}">
                                        <div class="card-body">
                                            <p class="mb-4">Payment Status</p>
                                            <p class="fs-30 mb-2">Cash {{ ucwords($transaction->payment_status) }}</p>
                                        </div>
                                    </div>

                                    <button class="btn btn-block btn-primary mb-4">Download Ticket</button>
                                @else
                                    <div class="card card-inverse-danger mb-3" style="cursor: pointer"
                                        data-id="{{ $transaction->billing_number }}">
                                        <div class="card-body">
                                            <p class="mb-4">Payment Status</p>
                                            <p class="fs-30 mb-2">Cash {{ ucwords($transaction->payment_status) }}</p>
                                        </div>
                                    </div>
                                @endif

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
                                                <td>{{ \Carbon\Carbon::parse($transaction->visit_date)->format('d F Y') }}
                                                </td>
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
                                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d F Y H:i:s') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                                                <th>Visit Date</th>
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
                                                <td>{{ \Carbon\Carbon::parse($item->visit_date)->format('d F Y') }}</td>
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

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="ticketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ticket Preview</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <iframe id="ticketFrame" src="" style="width:100%; height:500px; border:none;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Confirmation Details</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Transfer Amount</th>
                                    <th>Bank Name</th>
                                    <th>Account Name</th>
                                    <th>Account Number</th>
                                    <th>Proof of Payment</th>
                                    <th>Status</th>
                                    <th>Created At</th>

                                </tr>
                            </thead>
                            <tbody id="modalContent">
                                <!-- ✅ AJAX will populate this dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Proof of Payment</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <img id="modalImage" src="" class="img-fluid" alt="Bukti Transfer">
                        </div>
                        <div class="col-12 col-md-6">
                            <form method="post">
                                <input type="hidden" id="paymentId"> <!-- ✅ Hidden input for Payment ID -->

                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="custom-select">
                                        <option value="">-- Select Status --</option>
                                        <option value="0">Pending</option>
                                        <option value="1">Approve</option>
                                        <option value="2">Reject</option>
                                    </select>
                                </div>

                                <button type="button" class="btn btn-primary btn-block"
                                    id="btnApprovePayment">Submit</button>
                            </form>
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
        $(document).ready(function() {

            // ✅ Handle image modal
            $('#imageModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var imageUrl = button.data('image');
                var paymentId = button.data('id'); // ✅ Grab payment ID

                $('#modalImage').attr('src', imageUrl);
                $('#paymentId').val(paymentId); // ✅ Set hidden payment ID
            });

            // ✅ Handle status update submission
            $('#btnApprovePayment').on('click', function() {
                var paymentId = $('#paymentId').val();
                var newStatus = $('#status').val();

                if (newStatus === '') {
                    Swal.fire({
                        text: "Silakan pilih status!",
                        confirmButtonText: "OK",
                    });
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.payment.update') }}",
                    type: "POST",
                    data: {
                        id: paymentId,
                        status: newStatus,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            text: response.message,
                            confirmButtonText: "OK",
                        }).then(() => {
                            location.reload(); // ✅ Refresh page after success
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            text: "Terjadi kesalahan. Silakan coba lagi!",
                            confirmButtonText: "OK",
                        });
                    }
                });
            });
        });

        function openTicketModal(url) {
            document.getElementById("ticketFrame").src = url; // Load the ticket link into the iframe
            var ticketModal = new bootstrap.Modal(document.getElementById("ticketModal"));
            ticketModal.show();
        }
    </script>
@endpush
