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
                    <h5 class="modal-title" id="mappingUserModalLabel">Manage User Mapping</h5>
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
                        </div>

                        <div class="form-group">
                            <label for="destination_id">Destination</label>
                            <select class="form-control" id="destination_id" name="destination_id">
                                <option value="">Select Destination</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
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
                order: [[1, 'asc']], // Ensures sorting starts from "Name" instead of row_number
                columns: [
                    { data: null, name: 'row_number', orderable: false, searchable: false, render: function(data, type, row, meta) {
                        return meta.row + 1; // Generate row number dynamically
                    }},
                    { data: 'name', name: 'name' },
                    { data: 'destination_name', name: 'destination_name' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
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
                loadUsers();
                loadDestinations();
                $('#mappingUserModal').modal('show'); // Open modal
            });

            $('#btn-save-mapping-user').click(function() {
                let formData = {
                    user_id: $('#user_id').val(),
                    destination_id: $('#destination_id').val(),
                    role_id: $('#user_id option:selected').data('role'), // Assuming role comes from user selection
                    _token: $('input[name="_token"]').val()
                };

                $.ajax({
                    url: "{{ route('admin.mapping-users.store') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, "Success", { timeOut: 3000, progressBar: true });

                            $('#mappingUserModal').modal('hide'); // Close modal
                            $('#mappingUserForm')[0].reset(); // Reset form
                            $('#mapping-users-table').DataTable().ajax.reload(); // Refresh table
                        } else {
                            toastr.error(response.message, "Error", { timeOut: 3000, progressBar: true });
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Failed to save mapping", "Error", { timeOut: 3000, progressBar: true });
                    }
                });
            });
        });

        function loadUsers() {
            $.ajax({
                url: "{{ route('users.list') }}",
                type: "GET",
                success: function(response) {
                    $('#user_id').empty().append('<option value="">Select User</option>');
                    response.data.forEach(user => {
                        $('#user_id').append(`<option value="${user.id}">${user.name} (${user.role})</option>`);
                    });
                },
                error: function() {
                    toastr.error("Failed to load users", "Error", { timeOut: 3000, progressBar: true });
                }
            });
        }

        function loadDestinations() {
            $.ajax({
                url: "{{ route('destinations.list') }}",
                type: "GET",
                success: function(response) {
                    $('#destination_id').empty().append('<option value="">Select Destination</option>');
                    response.data.forEach(destination => {
                        $('#destination_id').append(`<option value="${destination.id}">${destination.name}</option>`);
                    });
                },
                error: function() {
                    toastr.error("Failed to load destinations", "Error", { timeOut: 3000, progressBar: true });
                }
            });
        }
    </script>
@endpush
