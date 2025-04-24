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
                                <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button"
                                    id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="mdi mdi-calendar"></i> Today (10 Jan 2021)
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                                    <a class="dropdown-item" href="#">January - March</a>
                                    <a class="dropdown-item" href="#">March - June</a>
                                    <a class="dropdown-item" href="#">June - August</a>
                                    <a class="dropdown-item" href="#">August - November</a>
                                </div>
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
                        <h4 class="card-title">Manajemen data user</h4>
                        <p class="card-description d-none">
                            Add class <code>.table-striped</code>
                        </p>

                        @if (session('role_id') !== 3)
                            <a type="button" class="btn btn-sm btn-primary" id="add-user-btn">Add New User</a>
                        @endif

                        <div class="table-responsive mt-2">
                            <table class="table table-striped" id="users-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Username</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role Name</th>
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

    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">Form Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form class="forms-sample" id="userForm">
                                        @csrf

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="role_id">Select Role</label>
                                                    <select class="form-control " id="role_id" name="role_id">
                                                        <option value="">Loading...</option> <!-- Populated via AJAX -->
                                                    </select>
                                                    <small class="form-text text-danger" id="role_id_error"></small>
                                                </div>

                                                <!-- Parent selection (hidden by default) -->
                                                <div class="form-group" id="parent_list_container" style="display:none;">
                                                    <label for="parent_list">Select Parent</label>
                                                    <select class="form-control" id="parent_list" name="parent_list">
                                                        <option value="">Loading...</option> <!-- AJAX will populate this -->
                                                    </select>
                                                    <small class="form-text text-danger" id="parent_list_error"></small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="username">Username</label>
                                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                                                    <small class="form-text text-danger" id="username_error"></small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                                                    <small class="form-text text-danger" id="name_error"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email address</label>
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                                    <small class="form-text text-danger" id="email_error"></small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">Phone</label>
                                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
                                                    <small class="form-text text-danger" id="phone_error"></small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-danger" id="password_error"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="confirm_password">Confirm Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="confirm_password">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-danger" id="confirm_password_error"></small>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btn-save">Simpan</button>
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
            $('.toggle-password').click(function() {
                let target = $(this).data('target'); // Get target input ID
                let input = $('#' + target); // Get input element
                let icon = $(this).find('i'); // Get icon

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text'); // Show password
                    icon.removeClass('fa-eye').addClass('fa-eye-slash'); // Change icon
                } else {
                    input.attr('type', 'password'); // Hide password
                    icon.removeClass('fa-eye-slash').addClass('fa-eye'); // Change icon back
                }
            });


            // Fetch roles via AJAX
            $.ajax({
                url: "{{ route('roles.list') }}",
                type: "GET",
                success: function(data) {
                    let roleDropdown = $("#role_id");
                    roleDropdown.empty();
                    roleDropdown.append('<option value="">Select Role</option>'); // Default option

                    data.forEach(role => {
                        roleDropdown.append(`<option value="${role.id}">${role.name}</option>`);
                    });
                }
            });

            // Fetch admins via AJAX
            $.ajax({
                url: "{{ route('admins.list') }}",
                type: "GET",
                success: function(data) {
                    let adminDropdown = $("#parent_list"); // Ensure this targets parent_list
                    adminDropdown.empty();
                    adminDropdown.append('<option value="">None</option>'); // Default option

                    data.forEach(admin => {
                        adminDropdown.append(`<option value="${admin.id}">${admin.name}</option>`);
                    });
                }
            });

            // Show/hide parent_list based on selected role
            $('#role_id').change(function() {
                let selectedRole = $(this).val();

                // Assuming "Operator" has role_id = 3
                if (selectedRole == 3) {
                    $('#parent_list_container').show();
                } else {
                    $('#parent_list_container').hide();
                    $('#parent_list').val(''); // Reset value when hidden
                }
            });

            $('#add-user-btn').click(function() {
                $('#formModal').modal('show'); // Show the modal
            });

            $('#btn-save').click(function() {
                let password = $('#password').val();
                let confirmPassword = $('#confirm_password').val();

                // Check if passwords match
                if (password !== confirmPassword) {
                    alert("Password and Confirm Password must match!");
                    return; // Stop execution if passwords don't match
                }

                let formData = {
                    _token: $('input[name="_token"]').val(),
                    role_id: $('#role_id').val(),
                    parent_list: $('#parent_list').val(),
                    username: $('#username').val(),
                    name: $('#name').val(),
                    email: $('#email').val(),
                    phone: $('#phone').val(),
                    password: password,
                    password_confirmation: confirmPassword,
                };

                // Clear previous validation errors
                $('.form-control').removeClass('is-invalid');
                $('.form-text.text-danger').text('');

                // Disable button and show "Processing..." text
                $('#btn-save').prop('disabled', true).text('Processing...');

                $.ajax({
                    url: "{{ route('users.store') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#formModal').modal('hide'); // Close modal on success
                            $('#users-table').DataTable().ajax.reload(); // Refresh DataTable
                        } else {
                            console.log("Insert failed:", response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // Laravel validation error
                            let errors = xhr.responseJSON.errors;

                            // Loop through errors and update UI
                            $.each(errors, function(field, messages) {
                                $('#' + field).addClass('is-invalid'); // Highlight input field
                                $('#' + field + '_error').text(messages[0]); // Show error message
                            });
                        } else {
                            console.log("Insert error:", xhr.responseJSON);
                        }
                    },
                    complete: function() {
                        // Enable button and reset text
                        $('#btn-save').prop('disabled', false).text('Simpan');
                    }
                });
            });

        });


        $(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.users.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, // Row number
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'role_name',
                        name: 'role_name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                language: {
                    emptyTable: "You don’t have any data.", // Custom message for no data
                }
            });
        });
    </script>
@endpush
