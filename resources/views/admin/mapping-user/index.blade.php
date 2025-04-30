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
                        <h4 class="card-title">Mapping User</h4>
                        <p class="card-description d-none">
                            Add class <code>.table-striped</code>
                        </p>

                        @if (session('role_id') !== 3)
                            <a type="button" class="btn btn-sm btn-primary" id="add-mapping-user-btn">Add New</a>
                        @endif

                        <button type="button" class="btn btn-sm btn-light" id="refresh-mapping-user-btn">Refresh</button>

                        <div class="table-responsive mt-2">
                            <table class="table table-striped" id="mapping-users-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Wisata</th>
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

    <div class="modal fade" id="mappingUserModal" tabindex="-1" aria-labelledby="mappingUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mappingUserModalLabel">Add New User Mapping</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="mappingUserForm">
                        @csrf
                        <input type="hidden" id="mapping_id">

                        <div class="form-group">
                            <label for="user_id">User</label>
                            <select class="form-control" id="user_id" name="user_id">
                                <option value="">Select User</option>
                            </select>
                            <small class="form-text text-danger" id="user_id_error_label"></small>
                        </div>

                        <div class="form-group">
                            <label for="destination_id">Destination</label>
                            <select class="form-control" id="destination_id" name="destination_id">
                                <option value="">Select Destination</option>
                            </select>
                            <small class="form-text text-danger" id="destination_id_error_label"></small>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="btn-save-mapping-user">Save</button>
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
    <script>
        function loadMappingUsers() {
            $('#mapping-users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.mapping-user.data') }}",
                order: [
                    [1, 'asc']
                ], // Ensures sorting starts from "Name" instead of row_number
                columns: [{
                        data: null,
                        name: 'row_number',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Generate row number dynamically
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'destination_name',
                        name: 'destination_name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        }


        $(document).ready(function() {
            loadMappingUsers();

            $('#refresh-mapping-user-btn').click(function() {
                $('#mapping-users-table').DataTable().ajax.reload();
            });

            $(document).on('click', '#add-mapping-user-btn', function() {
                $('#mapping_id').val('');
                $('#mappingUserForm')[0].reset(); // Reset form
                $('#mappingUserModalLabel').text('Add New User Mapping');
                $('#btn-save-mapping-user').text('Save')
                loadUsers();
                loadDestinations();
                $('#mappingUserModal').modal('show'); // Open modal
            });

            $('#btn-save-mapping-user').click(function() {
                let mappingId = $('#mapping_id').val();
                let formData = {
                    user_id: $('#user_id').val(),
                    destination_id: $('#destination_id').val(),
                    _token: $('input[name="_token"]').val()
                };

                let url = mappingId ? "{{ route('admin.mapping-users.update', ':id') }}".replace(':id',
                    mappingId) : "{{ route('admin.mapping-users.store') }}";
                let type = mappingId ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    type: type,
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, "Success", {
                                timeOut: 3000,
                                progressBar: true
                            });

                            $('#mappingUserModal').modal('hide');
                            $('#mappingUserForm')[0].reset();
                            $('#mapping-users-table').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.message, "Error", {
                                timeOut: 3000,
                                progressBar: true
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // Laravel validation errors
                            let errors = xhr.responseJSON.errors;
                            console.log('Validation errors:', errors); // Debugging step

                            // Clear previous validation styles and messages
                            $('.form-control').removeClass('is-invalid');
                            $('.form-text.text-danger').text('');

                            $.each(errors, function(field, messages) {
                                let fieldSelector = $('#' + field);
                                let errorSelector = $('#' + field + '_error_label');

                                if (fieldSelector.length > 0 && errorSelector.length >
                                    0) {
                                    fieldSelector.addClass(
                                    'is-invalid'); // Highlight invalid input
                                    errorSelector.text(messages[
                                    0]); // Show error message
                                } else {
                                    console.warn('Missing error element for:',
                                    field); // Debugging
                                }
                            });

                            toastr.error("Validation error! Please check the form.", "Error", {
                                timeOut: 3000,
                                progressBar: true
                            });
                        } else {
                            toastr.error("Error: " + xhr.responseJSON.message, "Error", {
                                timeOut: 3000,
                                progressBar: true
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.edit-mapping', function() {
                let mappingId = $(this).data('id');
                let userId = $(this).data('user');
                let destinationId = $(this).data('destination');

                console.log("Mapping ID:", mappingId, "User ID:", userId, "Destination ID:",
                destinationId); // Debugging

                $('#mapping_id').val(mappingId); // Set mapping ID first

                // Load users, then set the selected user after dropdown is populated
                loadUsers(function() {
                    $('#user_id').val(userId).trigger('change'); // Ensure selection updates
                });

                // Load destinations, then set the selected destination after dropdown is populated
                loadDestinations(function() {
                    $('#destination_id').val(destinationId).trigger(
                    'change'); // Ensure selection updates
                });

                $('#mappingUserModalLabel').text('Edit User Mapping');
                $('#btn-save-mapping-user').text('Update');
                $('#mappingUserModal').modal('show'); // Open modal after values are set
            });

            $(document).on('click', '.delete-mapping', function() {
                let mappingId = $(this).data('id'); // Ensure ID is retrieved

                if (!mappingId) {
                    toastr.error("Missing mapping ID", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    });
                    return;
                }

                Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone!",
                    // icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.mapping-users.destroy', ':id') }}"
                                .replace(':id', mappingId),
                            type: "DELETE",
                            data: {
                                _token: $('input[name="_token"]').val()
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message, "Success", {
                                        timeOut: 3000,
                                        progressBar: true
                                    });
                                    $('#mapping-users-table').DataTable().ajax
                                .reload(); // Refresh table
                                } else {
                                    toastr.error(response.message, "Error", {
                                        timeOut: 3000,
                                        progressBar: true
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire("Failed!", "Unable to delete mapping",
                                    "error");
                            }
                        });
                    }
                });
            });
        });

        function loadUsers(callback) {
            $.ajax({
                url: "{{ route('users.list') }}",
                type: "GET",
                success: function(response) {
                    $('#user_id').empty().append('<option value="">Select User</option>');
                    response.data.forEach(user => {
                        $('#user_id').append(
                            `<option value="${user.id}">${user.name} (${user.role})</option>`);
                    });

                    if (callback) callback(); // Set selected user after loading completes
                }
            });
        }

        function loadDestinations(callback) {
            $.ajax({
                url: "{{ route('destinations.list') }}",
                type: "GET",
                success: function(response) {
                    $('#destination_id').empty().append('<option value="">Select Destination</option>');
                    response.data.forEach(destination => {
                        $('#destination_id').append(
                            `<option value="${destination.id}">${destination.name}</option>`);
                    });

                    if (callback) callback(); // Set selected destination after loading completes
                }
            });
        }
    </script>
@endpush
