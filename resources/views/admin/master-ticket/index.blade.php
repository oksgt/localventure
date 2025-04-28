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
                        <h4 class="card-title">Master Ticket</h4>
                        <p class="card-description d-none">
                            Add class <code>.table-striped</code>
                        </p>

                        @if (session('role_id') !== 3)
                            <a type="button" class="btn btn-sm btn-primary" id="add-master-ticket-btn">Add New</a>
                        @endif

                        <button type="button" class="btn btn-sm btn-light" id="refresh-master-ticket-btn">Refresh</button>

                        <div class="table-responsive mt-2">
                            <table class="table table-striped" id="master-tickets-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Day Type<br>(Weekend/Weekday)</th>
                                        <th>Base Price</th>
                                        <th>Insurance Price</th>
                                        <th>Final Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="pricingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Ticket Pricing</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="pricingForm">
                        @csrf
                        <input type="hidden" id="pricing_id">

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Destination</label>
                                    <select id="destination_id" name = "destination_id" class="form-control"></select>
                                    <small class="form-text text-danger" id="destination_id_error"></small>
                                </div>

                                <div class="form-group">
                                    <label>Guest Category</label>
                                    <select id="guest_type_id" name = "guest_type_id" class="form-control"></select>
                                    <small class="form-text text-danger" id="guest_type_id_error"></small>
                                </div>

                                <div class="form-group">
                                    <label>Day Type (Weekend/Weekday)</label>
                                    <select id="day_type" name="day_type" class="form-control">
                                        <option value="">Select Day Type</option>
                                        <option value="weekday">Weekday</option>
                                        <option value="weekend">Weekend</option>
                                    </select>
                                    <small class="form-text text-danger" id="day_type_error"></small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Base Price</label>
                                    <input type="text" id="base_price" name= "base_price" class="form-control input-currency">
                                    <small class="form-text text-danger" id="base_price_error"></small>
                                </div>

                                <div class="form-group">
                                    <label>Insurance Price</label>
                                    <input type="text" id="insurance_price" name="insurance_price" class="form-control input-currency">
                                    <small class="form-text text-danger" id="insurance_price_error"></small>
                                </div>

                                <div class="form-group">
                                    <label>Final Price</label>
                                    <input type="text" id="final_price" class="form-control input-currency" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btn-save-pricing">Save</button>
                        </div>
                    </form>
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
        function loadPricing() {
            $('#master-tickets-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.master-ticket.data') }}",
                columns: [
                    { data: null, name: 'row_number', orderable: false, searchable: false, render: function(data, type, row, meta) {
                        return meta.row + 1; // Generate row number dynamically
                    }},
                    { data: 'destination_name', name: 'destination_name' },
                    { data: 'category', name: 'category' },
                    { data: 'day_type', name: 'day_type' },
                    { data: 'base_price', name: 'base_price', render: function(data) {
                        return formatCurrency(data); // Format base price
                    }},
                    { data: 'insurance_price', name: 'insurance_price', render: function(data) {
                        return formatCurrency(data); // Format insurance price
                    }},
                    { data: 'final_price', name: 'final_price', render: function(data) {
                        return formatCurrency(data); // Format final price
                    }},
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        }


        $(document).ready(function() {

            new AutoNumeric('#base_price', {
                currencySymbol: 'Rp ',
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                currencySymbolPlacement: 'p',
                unformatOnSubmit: true
            });

            new AutoNumeric('#insurance_price', {
                currencySymbol: 'Rp ',
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                currencySymbolPlacement: 'p',
                unformatOnSubmit: true
            });

            new AutoNumeric('#final_price', {
                currencySymbol: 'Rp ',
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                currencySymbolPlacement: 'p',
                unformatOnSubmit: true
            });


            loadPricing();
            $('#refresh-master-ticket-btn').click(function() {
                $('#master-tickets-table').DataTable().ajax.reload();
            });

            $('#btn-save-pricing').click(function() {
                let pricingId = $('#pricing_id').val();
                let formData = {
                    destination_id: $('#destination_id').val(),
                    guest_type_id: $('#guest_type_id').val(),
                    day_type: $('#day_type').val(),
                    base_price: AutoNumeric.getNumber('#base_price'),
                    insurance_price: AutoNumeric.getNumber('#insurance_price'),
                    _token: $('input[name="_token"]').val()
                };

                console.log('object:', formData);

                let url = pricingId ? "{{ route('admin.master-ticket.update', ':id') }}".replace(':id', pricingId) : "{{ route('admin.master-ticket.store') }}";
                let type = pricingId ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    type: type,
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, "Success");
                            $('#pricingModal').modal('hide');
                            $('#pricingForm')[0].reset();
                            $('#master-tickets-table').DataTable().ajax.reload();
                            clearValidationErrors(); // Reset validation styles
                        } else {
                            toastr.error(response.message, "Error");
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // Laravel validation error
                            applyValidationErrors(xhr.responseJSON.errors);
                        } else {
                            toastr.error("Failed to save pricing", "Error");
                        }
                    }
                });
            });

            $(document).on('click', '#add-master-ticket-btn', function() {
                $('#pricing_id').val('');
                $('#pricingForm')[0].reset();

                loadDestinations(function() {
                    loadGuestTypes(function() {
                        //reset form
                        $('#pricingForm')[0].reset();
                        $('#pricingModal').modal('show'); // Open modal after data is set
                    });
                });
            });

            $('#base_price, #insurance_price').on('input', calculateFinalPrice);
        });

        function loadDestinations(callback) {
            $.ajax({
                url: "{{ route('destinations.list') }}",
                type: "GET",
                success: function(response) {
                    $('#destination_id').empty().append('<option value="">Select Destination</option>');
                    response.data.forEach(destination => {
                        $('#destination_id').append(`<option value="${destination.id}">${destination.name}</option>`);
                    });

                    if (callback) callback(); // Proceed to next function after loading completes
                }
            });
        }

        function loadGuestTypes(callback) {
            $.ajax({
                url: "{{ route('guest-types.list') }}",
                type: "GET",
                success: function(response) {
                    $('#guest_type_id').empty().append('<option value="">Select Category</option>');
                    response.data.forEach(guestType => {
                        $('#guest_type_id').append(`<option value="${guestType.id}">${guestType.name}</option>`);
                    });

                    if (callback) callback(); // Proceed after data loads
                }
            });
        }

        function calculateFinalPrice() {
            let basePrice = AutoNumeric.getNumber('#base_price') || 0; // Get raw number
            let insurancePrice = AutoNumeric.getNumber('#insurance_price') || 0; // Get raw number

            let finalPrice = basePrice + insurancePrice;
            AutoNumeric.set('#final_price', finalPrice); // Format final price dynamically
        }

        function applyValidationErrors(errors) {
            clearValidationErrors(); // Remove previous errors

            $.each(errors, function(key, value) {
                $('#' + key).addClass('is-invalid'); // Highlight input
                $('#' + key + '_error').text(value[0]); // Show error message
            });
        }

        function clearValidationErrors() {
            $('.form-control').removeClass('is-invalid'); // Remove red borders
            $('.form-text.text-danger').text(''); // Clear error messages
        }

        function formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
        }

    </script>
@endpush
