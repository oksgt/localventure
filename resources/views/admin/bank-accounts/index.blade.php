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
                        <h4 class="card-title">Bank Accounts</h4>
                        <p class="card-description d-none">
                            Add class <code>.table-striped</code>
                        </p>

                        @if (session('role_id') !== 3)
                            <a type="button" class="btn btn-sm btn-primary" id="add-bank-account-btn">Add New</a>
                        @endif

                        <button type="button" class="btn btn-sm btn-light" id="refresh-bank-account-btn">Refresh</button>

                        <div class="table-responsive mt-2">
                            <table class="table table-striped" id="bank-accounts-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Bank Name</th>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                        <th>Status</th>
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

    <!-- Bank Account Modal -->
    <div class="modal fade" id="bankAccountModal" tabindex="-1" role="dialog" aria-labelledby="bankAccountModalLabel"
    aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="bankAccountForm">
                    @csrf
                    <input type="hidden" id="bank_account_id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="bankAccountModalLabel">Add Bank Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="bank_name">Bank Name</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Enter Bank Name">
                            <small class="form-text text-danger" id="bank_name_error"></small>
                        </div>

                        <div class="form-group">
                            <label for="account_name">Account Name</label>
                            <input type="text" class="form-control" id="account_name" name="account_name" placeholder="Enter Account Name">
                            <small class="form-text text-danger" id="account_name_error"></small>
                        </div>

                        <div class="form-group">
                            <label for="account_number">Account Number</label>
                            <input type="text" class="form-control" id="account_number" name="account_number" placeholder="Enter Account Number">
                            <small class="form-text text-danger" id="account_number_error"></small>
                        </div>

                        <div class="form-group">
                            <label for="is_public">Status</label>
                            <select class="form-control" id="is_public" name="is_public">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <small class="form-text text-danger" id="is_public_error"></small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="bankAccountSubmitBtn">Save</button>
                    </div>
                </form>
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
            $('#bank-accounts-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.bank-accounts.data') }}",
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
                    { data: 'bank_name', name: 'bank_name' },
                    { data: 'account_name', name: 'account_name' },
                    { data: 'account_number', name: 'account_number' },
                    {
                        data: 'is_public',
                        name: 'is_public',
                        render: function(data) {
                            return data == 1 ? '<span class="badge badge-success">Available</span>' : '<span class="badge badge-secondary">Private</span>';
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            $(document).on('click', '#refresh-bank-account-btn', function () {
                $('#bank-accounts-table').DataTable().ajax.reload(); // Reload DataTable
            });

            $(document).on('click', '#add-bank-account-btn', function() {
                $('#bank_account_id').val(''); // Reset hidden ID field (new entry)
                $('#bankAccountModalLabel').text('Add Bank Account'); // Set modal title
                $('#bankAccountSubmitBtn').text('Save'); // Set button text
                $('#bankAccountModal').modal('show'); // Open modal
            });

            $(document).on('click', '.edit-account', function() {
                let id = $(this).data('id');

                $.ajax({
                    url: "{{ route('admin.bank-accounts.edit', ':id') }}".replace(':id', id),
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            $('#bank_account_id').val(response.data.id);
                            $('#bank_name').val(response.data.bank_name);
                            $('#account_name').val(response.data.account_name);
                            $('#account_number').val(response.data.account_number);
                            $('#is_public').val(response.data.is_public);

                            $('#bankAccountModalLabel').text('Edit Bank Account'); // Update modal title
                            $('#bankAccountSubmitBtn').text('Update'); // Change button text
                            $('#bankAccountModal').modal('show'); // Open modal
                        } else {
                            toastr.error("Failed to fetch bank account data.", "Error");
                        }
                    },
                    error: function() {
                        toastr.error("Failed to load data.", "Error");
                    }
                });
            });

            $('#bankAccountForm').submit(function(e) {
                e.preventDefault();

                let id = $('#bank_account_id').val(); // Check if an ID exists (for update)
                let url = id ? "{{ route('admin.bank-accounts.update', ':id') }}".replace(':id', id) : "{{ route('admin.bank-accounts.store') }}";
                let type = id ? "PUT" : "POST";

                let formData = {
                    _token: $('input[name="_token"]').val(),
                    _method: type,
                    bank_name: $('#bank_name').val(),
                    account_name: $('#account_name').val(),
                    account_number: $('#account_number').val(),
                    is_public: $('#is_public').val(),
                };

                $('#bankAccountSubmitBtn').prop('disabled', true).text(id ? 'Processing...' : 'Processing...');

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, "Success", { timeOut: 3000, progressBar: true });

                            $('#bankAccountModal').modal('hide');
                            $('#bankAccountForm')[0].reset();
                            $('#bank-accounts-table').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.message, "Error", { timeOut: 3000, progressBar: true });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // Laravel validation errors
                            let errors = xhr.responseJSON.errors;
                            $('.form-control').removeClass('is-invalid');
                            $('.form-text.text-danger').text('');

                            $.each(errors, function(field, messages) {
                                $('#' + field).addClass('is-invalid');
                                $('#' + field + '_error').text(messages[0]);
                            });

                            toastr.error("Validation error! Please check the form.", "Error", { timeOut: 3000, progressBar: true });
                        } else {
                            toastr.error("Error: " + xhr.responseJSON.message, "Error", { timeOut: 3000, progressBar: true });
                        }
                    },
                    complete: function() {
                        $('#bankAccountSubmitBtn').prop('disabled', false).text(id ? 'Update' : 'Save');
                    }
                });
            });
        });

        $(document).on('click', '.delete-account', function () {
            let accountId = $(this).data('id'); // Get the account ID
            let url = "{{ route('admin.bank-accounts.destroy', ':id') }}".replace(':id', accountId);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                // icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}" // Include CSRF token for security
                        },
                        success: function(response) {
                            toastr.success(response.message, "Success", { timeOut: 3000, progressBar: true });
                            $('#bank-accounts-table').DataTable().ajax.reload(); // Refresh table
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                toastr.error(errors[0], "Error", { timeOut: 3000, progressBar: true });
                            } else {
                                toastr.error("Failed to delete bank account.", "Error", { timeOut: 3000, progressBar: true });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
