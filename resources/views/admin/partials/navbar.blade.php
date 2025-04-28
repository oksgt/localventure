<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center ">
        <a class="navbar-brand brand-logo" href="{{ route('admin.home') }}"><img src="{{ asset('storage/assets/image/logo-utama.png') }}" class="mr-2"
                alt="logo" /></a>
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="{{ asset('storage/assets/image/logo-utama.png') }}" alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>

        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                    <i class="ti-settings"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">

                    <button class="dropdown-item" id="btn-profile">
                        <i class="ti-user text-primary"></i>
                        Update Profile
                    </button>
                    <button class="dropdown-item" id="btn-change-password">
                        <i class="ti-key text-primary"></i>
                        Update Password
                    </button>
                    <form method="POST" action="{{ route('logout.process') }}">
                        @csrf
                        <button type="submit" class="dropdown-item border-0 bg-transparent">
                            <i class="ti-power-off text-primary"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>

<div class="modal fade" id="formProfile" tabindex="-1" aria-labelledby="formProfileLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formProfileLabel">Update Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <form class="forms-sample" id="profileForm">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" class="form-control" id="username_profile"
                                                    name="username_profile" placeholder="Username">
                                                <small class="form-text text-danger" id="username_error"></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" id="name_profile" name="name_profile"
                                                    placeholder="Name">
                                                <small class="form-text text-danger" id="name_error"></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email address</label>
                                                <input type="email" class="form-control" id="email_profile"
                                                    name="email_profile" placeholder="Email">
                                                <small class="form-text text-danger" id="email_error"></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="text" class="form-control" id="phone_profile"
                                                    name="phone_profile" placeholder="Phone">
                                                <small class="form-text text-danger" id="phone_error"></small>
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
                <button type="button" class="btn btn-primary" id="btn-save-update-profile">Update</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="formChangePassword" tabindex="-1" aria-labelledby="formChangePasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formChangePasswordLabel">Update Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <form class="forms-sample" id="changePasswordForm">
                                    @csrf

                                    <div class="form-group">
                                        <label for="password">Old Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="old_password"
                                                name="old_password" placeholder="Old Password" autocomplete="off">
                                            <div class="input-group-append">
                                                <button type="button"
                                                    class="btn btn-outline-secondary toggle-password"
                                                    data-target="old_password">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text text-danger" id="old_password_error"></small>
                                    </div>

                                    <div class="form-group">
                                        <label for="password">New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="new_password"
                                                name="new_password" placeholder="New Password">
                                            <div class="input-group-append">
                                                <button type="button"
                                                    class="btn btn-outline-secondary toggle-password"
                                                    data-target="new_password">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text text-danger" id="new_password_error"></small>
                                    </div>

                                    <div class="form-group">
                                        <label for="confirm_password">Confirm New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_new_password"
                                                name="confirm_new_password" placeholder="Confirm new Password">
                                            <div class="input-group-append">
                                                <button type="button"
                                                    class="btn btn-outline-secondary toggle-password"
                                                    data-target="confirm_new_password">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text text-danger"
                                            id="confirm_new_password_error"></small>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn-save-change-password">Update</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#btn-change-password').click(function() {
                $('#formChangePassword').modal('show'); // Open modal
            });

            $('#btn-save-change-password').click(function() {
                let formData = {
                    _token: $('input[name="_token"]').val(),
                    _method: "PUT",
                    old_password: $('#old_password').val(),
                    new_password: $('#new_password').val(),
                    new_password_confirmation: $('#confirm_new_password').val(),
                };

                $('#btn-save-change-password').prop('disabled', true).text('Processing...');

                $.ajax({
                    url: "{{ route('profile.updatePassword') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success("Password updated successfully. Redirecting to login...", "Success", { timeOut: 3000, progressBar: true });

                            // Redirect user to login page after success
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 3000);
                        } else {
                            toastr.error(response.message, "Error", { timeOut: 3000, progressBar: true });
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Failed to update password", "Error", { timeOut: 3000, progressBar: true });
                    },
                    complete: function() {
                        $('#btn-save-change-password').prop('disabled', false).text('Update');
                    }
                });
            });

            $('#btn-profile').click(function() {
                $.ajax({
                    url: "{{ route('profile.get') }}",
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            let user = response.data;

                            // Fill form with existing user data
                            $('#username_profile').val(user.username);
                            $('#name_profile').val(user.name);
                            $('#email_profile').val(user.email);
                            $('#phone_profile').val(user.phone);

                            $('#formProfile').modal('show'); // Open modal
                        } else {
                            toastr.error("Failed to retrieve profile data", "Error", { timeOut: 3000, progressBar: true });
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Error fetching profile data", "Error", { timeOut: 3000, progressBar: true });
                    }
                });
            });

            $('#btn-save-update-profile').click(function() {
                let formData = {
                    _token: $('input[name="_token"]').val(),
                    _method: "PUT", // Laravel expects PUT for updates
                    username_profile: $('#username_profile').val(),
                    name_profile: $('#name_profile').val(),
                    email_profile: $('#email_profile').val(),
                    phone_profile: $('#phone_profile').val(),
                };

                $('#btn-save-update-profile').prop('disabled', true).text('Processing...');

                $.ajax({
                    url: "{{ route('profile.update') }}",
                    type: "POST", // Laravel requires POST with "_method: PUT"
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, "Success", { timeOut: 3000, progressBar: true });
                            $('#formProfile').modal('hide'); // Close modal
                            //reload current page to reflect changes
                            location.reload();
                        } else {
                            toastr.error(response.message, "Error", { timeOut: 3000, progressBar: true });
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Failed to update profile", "Error", { timeOut: 3000, progressBar: true });
                    },
                    complete: function() {
                        $('#btn-save-update-profile').prop('disabled', false).text('Update');
                    }
                });
            });
        });
    </script>
@endpush
