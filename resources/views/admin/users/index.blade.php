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
                                                        <option value="">Loading...</option>
                                                        <!-- Populated via AJAX -->
                                                    </select>
                                                    <small class="form-text text-danger" id="role_id_error"></small>
                                                </div>

                                                <!-- Parent selection (hidden by default) -->
                                                <div class="form-group" id="parent_list_container" style="display:none;">
                                                    <label for="parent_list">Select Parent</label>
                                                    <select class="form-control" id="parent_list" name="parent_list">
                                                        <option value="">Loading...</option>
                                                        <!-- AJAX will populate this -->
                                                    </select>
                                                    <small class="form-text text-danger" id="parent_list_error"></small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="username">Username</label>
                                                    <input type="text" class="form-control" id="username"
                                                        name="username" placeholder="Username">
                                                    <small class="form-text text-danger" id="username_error"></small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        placeholder="Name">
                                                    <small class="form-text text-danger" id="name_error"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email address</label>
                                                    <input type="email" class="form-control" id="email"
                                                        name="email" placeholder="Email">
                                                    <small class="form-text text-danger" id="email_error"></small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">Phone</label>
                                                    <input type="text" class="form-control" id="phone"
                                                        name="phone" placeholder="Phone">
                                                    <small class="form-text text-danger" id="phone_error"></small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="password"
                                                            name="password" placeholder="Password">
                                                        <div class="input-group-append">
                                                            <button type="button"
                                                                class="btn btn-outline-secondary toggle-password"
                                                                data-target="password">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-danger" id="password_error"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="confirm_password">Confirm Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="confirm_password"
                                                            name="confirm_password" placeholder="Confirm Password">
                                                        <div class="input-group-append">
                                                            <button type="button"
                                                                class="btn btn-outline-secondary toggle-password"
                                                                data-target="confirm_password">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-danger"
                                                        id="confirm_password_error"></small>
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

            let currentRole = "{{ session('role_id') }}"; // Logged-in user's role
            let currentUserId = "{{ session('user_id') }}"; // Logged-in user's ID

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

                    // Superadmin (role_id = 1) gets Admin & Operator
                    // Admin (role_id = 2) gets only Operator
                    let filteredRoles = data.filter(role => {
                        return currentRole == 1 ? [2, 3].includes(role.id) : role.id == 3;
                    });

                    filteredRoles.forEach(role => {
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
                        adminDropdown.append(
                            `<option value="${admin.id}">${admin.name}</option>`);
                    });
                }
            });

            $('#role_id').change(function() {
                let selectedRole = $(this).val();

                if (selectedRole == 3) { // If role is Operator
                    $('#parent_list_container').show();
                    let parentDropdown = $('#parent_list');
                    parentDropdown.empty();

                    if (currentRole == 1) {
                        // Superadmin sees all Admins as possible parents
                        $.ajax({
                            url: "{{ route('admins.list') }}",
                            type: "GET",
                            success: function(data) {
                                parentDropdown.append('<option value="">Select Parent</option>'); // Default option
                                data.forEach(admin => {
                                    parentDropdown.append(`<option value="${admin.id}">${admin.name}</option>`);
                                });
                            }
                        });
                    } else if (currentRole == 2) {
                        // Admin sets itself as the only parent option
                        parentDropdown.append(`<option value="${currentUserId}" selected>${"{{ session('name') }}"}</option>`);
                    }
                } else {
                    $('#parent_list_container').hide();
                    $('#parent_list').val('');
                }
            });


            $('#add-user-btn').click(function() {
                $('#formModal').modal('show'); // Show the modal
            });

            $('#btn-save').click(function() {
                let userId = $(this).data('user-id'); // Get user ID if updating

                let password = $('#password').val();
                let confirmPassword = $('#confirm_password').val();

                // Check if passwords match
                if (password !== confirmPassword) {
                    toastr.error("Password and Confirm Password must match!", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    });
                    return;
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

                // If updating an existing user, include method override
                if (userId) {
                    formData._method = "PUT";
                }

                $('.form-control').removeClass('is-invalid');
                $('.form-text.text-danger').text('');

                $('#btn-save').prop('disabled', true).text('Processing...');

                $.ajax({
                    url: userId ? "{{ route('users.update', ':id') }}".replace(':id', userId) : "{{ route('users.store') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(userId ? "User updated successfully!" : "User created successfully!", "Success", {
                                timeOut: 3000,
                                progressBar: true
                            });
                            $('#formModal').modal('hide');
                            $('#users-table').DataTable().ajax.reload();
                        } else {
                            toastr.error("Operation failed: " + response.message, "Error", {
                                timeOut: 3000,
                                progressBar: true
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // Laravel validation error
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                $('#' + field).addClass('is-invalid');
                                $('#' + field + '_error').text(messages[0]);
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
                    },
                    complete: function() {
                        $('#btn-save').prop('disabled', false).text(userId ? "Update" : "Simpan");
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

        function editUser(userId) {
            $.ajax({
                url: "{{ route('users.edit', ':id') }}".replace(':id', userId),
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        let user = response.data;

                        $('#role_id').val(user.role_id).trigger('change');
                        $('#username').val(user.username);
                        $('#name').val(user.name);
                        $('#email').val(user.email);
                        $('#phone').val(user.phone);
                        $('#parent_list').val(user.parent_id).trigger('change');

                        $('#password').closest('.form-group').hide();
                        $('#confirm_password').closest('.form-group').hide();

                        $('#formModalLabel').text("Update User");
                        $('#btn-save').data('user-id', user.id).text("Update"); // Set user ID for update

                        $('#formModal').modal('show');
                    } else {
                        toastr.error("Failed to fetch user data");
                    }
                },
                error: function(xhr) {
                    toastr.error("Error fetching user data");
                }
            });
        }

        function updateUser(userId) {
            let formData = {
                _token: $('input[name="_token"]').val(),
                _method: "PUT", // Laravel requires PUT for updates
                role_id: $('#role_id').val(),
                parent_list: $('#parent_list').val(),
                username: $('#username').val(),
                name: $('#name').val(),
                email: $('#email').val(),
                phone: $('#phone').val(),
            };

            $('#btn-save').prop('disabled', true).text('Processing...');

            $.ajax({
                url: "{{ route('users.update', ':id') }}".replace(':id', userId),
                type: "POST", // Laravel requires POST with "_method: PUT"
                data: formData,
                success: function(response) {
                    if (response.success) {
                        toastr.success("User updated successfully!");
                        $('#formModal').modal('hide');
                        $('#users-table').DataTable().ajax.reload();
                    } else {
                        toastr.error("Update failed: " + response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error("Update error: " + xhr.responseJSON.message);
                },
                complete: function() {
                    $('#btn-save').prop('disabled', false).text('Update');
                }
            });
        }

        function deleteUser(userId) {
            Swal.fire({
                title: "Are you sure?",
                text: "This user will be deleted. You won't be able to revert this!",
                // icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('users.delete', ':id') }}".replace(':id', userId),
                        type: "DELETE",
                        data: { _token: $('input[name="_token"]').val() },
                        beforeSend: function() {
                            toastr.info("Processing deletion...", { timeOut: 2000, progressBar: true });
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message, "Success", { timeOut: 3000, progressBar: true });
                                $('#users-table').DataTable().ajax.reload();
                            } else {
                                toastr.error(response.message, "Error", { timeOut: 3000, progressBar: true });
                            }
                        },
                        error: function(xhr) {
                            toastr.error("Failed to delete user!", "Error", { timeOut: 3000, progressBar: true });
                            console.log("Delete error:", xhr.responseJSON);
                        }
                    });
                }
            });
        }
    </script>
@endpush
