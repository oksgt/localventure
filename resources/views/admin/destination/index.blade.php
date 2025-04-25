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

                        <button type="button" class="btn btn-sm btn-light" id="refresh-user-btn">Refresh</button>

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

    </script>
@endpush
