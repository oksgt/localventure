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
                        <h4 class="card-title">Payment Option</h4>
                        <p class="card-description d-none">
                            Add class <code>.table-striped</code>
                        </p>
                        <div class="table-responsive mt-2">
                            <table class="table table-striped" id="payment-options-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Option Name</th>
                                        <th>Image</th>
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

    <!-- Add Payment Type Modal -->
    <div class="modal fade" id="addPaymentTypeModal" tabindex="-1" aria-labelledby="addPaymentTypeLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addPaymentTypeForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPaymentTypeLabel">Add New Payment Type</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <!-- Payment Type Name -->
                        <div class="form-group">
                            <label for="paymentTypeName">Payment Type Name</label>
                            <input type="text" class="form-control" id="paymentTypeName" name="payment_type_name">
                            <small class="form-text text-danger" id="payment_type_name_error"></small>
                        </div>
                        <!-- Status -->
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <small class="form-text text-danger" id="status_error"></small>
                        </div>
                        <!-- Payment Image -->
                        <div class="form-group">
                            <label for="paymentImage">Payment Image</label>
                            <input type="file" class="form-control-file" id="paymentImage" name="payment_image">
                            <img id="imagePreview" src="#" alt="Preview"
                                style="display: none; width: 100px; margin-top: 10px;">
                            <small class="form-text text-danger" id="payment_image_error"></small>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Payment Type Modal -->
    <div class="modal fade" id="editPaymentTypeModal" tabindex="-1" aria-labelledby="editPaymentTypeLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editPaymentTypeForm">
                    @csrf
                    <input type="hidden" name="id" id="editPaymentTypeId">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPaymentTypeLabel">Edit Payment Type</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <!-- Payment Type Name -->
                        <div class="form-group">
                            <label for="editPaymentTypeName">Payment Type Name</label>
                            <input type="text" class="form-control" id="editPaymentTypeName"
                                name="payment_type_name">
                            <small class="form-text text-danger" id="edit_payment_type_name_error"></small>
                        </div>
                        <!-- Status -->
                        <div class="form-group">
                            <label for="editStatus">Status</label>
                            <select class="form-control" id="editStatus" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <small class="form-text text-danger" id="edit_status_error"></small>
                        </div>
                        <!-- Payment Image -->
                        <div class="form-group">
                            <label for="editPaymentImage">Payment Image</label>
                            <input type="file" class="form-control-file" id="editPaymentImage" name="payment_image">
                            <img id="editImagePreview" src="#" alt="Preview"
                                style="display: none; width: 100px; margin-top: 10px;">
                            <small class="form-text text-danger" id="edit_payment_image_error"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#payment-options-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.payment-option.data') }}",
                columns: [{
                        data: null,
                        name: 'row_number',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // ✅ Auto-generate row number
                        }
                    },
                    {
                        data: 'payment_type_name',
                        name: 'payment_type_name'
                    },
                    {
                        data: 'payment_image',
                        name: 'payment_image',
                        render: function(data) {
                            return data ? `<img src="{{ asset('storage/') }}/${data}" width="50">` :
                                '-';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            return data == 1 ? '<span class="badge badge-success">Active</span>' :
                                '<span class="badge badge-danger">Inactive</span>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#add-bank-account-btn').on('click', function() {
                //reset form
                $('#addPaymentTypeForm')[0].reset();
                $('#imagePreview').hide(); // ✅ Hide image preview
                $('#addPaymentTypeModal').modal('show'); // ✅ Opens modal
            });


            // ✅ Delete Payment Type
            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This payment type will be permanently deleted.",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "Cancel",
                    customClass: {
                        title: "swal-title",
                        popup: "swal-popup"
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.payment-option.delete', ':id') }}"
                                .replace(':id', id),
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}" // Include CSRF token for security
                            },
                            success: function(response) {
                                $('#payment-options-table').DataTable().ajax
                            .reload(); // ✅ Refresh table
                                toastr.success(response.message, "Success", {
                                    timeOut: 3000,
                                    progressBar: true
                                });
                            },
                            error: function(xhr) {
                                if (xhr.status === 422) {
                                    let errors = xhr.responseJSON.errors;
                                    toastr.error(errors[0], "Error", {
                                        timeOut: 3000,
                                        progressBar: true
                                    });
                                } else {
                                    toastr.error("Failed to delete payment type.",
                                        "Error", {
                                            timeOut: 3000,
                                            progressBar: true
                                        });
                                }
                            }
                        });
                    }
                });
            });

            // ✅ Edit Payment Type (Load Data)
            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.payment-option.edit', ':id') }}".replace(':id', id),
                    type: "GET",
                    success: function(data) {
                        // ✅ Populate form fields
                        $('#editPaymentTypeName').val(data.payment_type_name);
                        $('#editStatus').val(data.status);
                        $('#editPaymentTypeId').val(data.id);

                        // ✅ Load existing image into preview
                        if (data.payment_image) {
                            $('#editImagePreview').attr('src',"{{ asset('storage') }}/" + data.payment_image);
                            $('#editImagePreview').show();
                        } else {
                            $('#editImagePreview').hide();
                        }

                        $('#editPaymentTypeModal').modal('show');
                    }
                });
            });

            $('#addPaymentTypeForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this); // ✅ Handle file upload

                $.ajax({
                    url: "{{ route('admin.payment-option.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr.success(response.message, "Success", {
                            timeOut: 3000,
                            progressBar: true
                        });
                        $('#addPaymentTypeModal').modal('hide'); // ✅ Close modal
                        $('#payment-options-table').DataTable().ajax
                            .reload(); // ✅ Refresh DataTable
                        resetValidation
                    (); // ✅ Clear validation errors after successful submission
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.error;
                        toastr.error(response.message, "Success", {
                            timeOut: 3000,
                            progressBar: true
                        });
                        // ✅ Reset previous errors
                        resetValidation();

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            toastr.error(errors[0], "Error", {
                                timeOut: 3000,
                                progressBar: true
                            });
                        } else {
                            toastr.error("Failed to add payment type.", "Error", {
                                timeOut: 3000,
                                progressBar: true
                            });
                        }

                        // ✅ Show validation errors dynamically
                        if (errors.payment_type_name) {
                            $('#paymentTypeName').addClass('is-invalid');
                            $('#payment_type_name_error').text(errors.payment_type_name[0]);
                        }

                        if (errors.status) {
                            $('#status').addClass('is-invalid');
                            $('#status_error').text(errors.status[0]);
                        }

                        if (errors.payment_image) {
                            $('#paymentImage').addClass('is-invalid');
                            $('#payment_image_error').text(errors.payment_image[0]);
                        }
                    }
                });
            });

            // ✅ Function to Clear Validation Errors
            function resetValidation() {
                $('.form-text.text-danger').text(''); // ✅ Clear error messages
                $('.is-invalid').removeClass('is-invalid'); // ✅ Remove invalid class
            }

            document.getElementById('paymentImage').addEventListener('change', function(event) {
                let file = event.target.files[0];

                if (file) {
                    let reader = new FileReader();

                    reader.onload = function(e) {
                        let imagePreview = document.getElementById('imagePreview');
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block'; // ✅ Show image preview
                    };

                    reader.readAsDataURL(file);
                }
            });

            $('#editPaymentTypeForm').on('submit', function(e) {
                e.preventDefault();

                let id = $('#editPaymentTypeId').val(); // ✅ Ensure ID is tracked
                let formData = new FormData(this); // ✅ Handle file uploads

                $.ajax({
                    url: "{{ route('admin.payment-option.update', ':id') }}".replace(':id', id),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#editPaymentTypeModal').modal('hide'); // ✅ Close modal
                        toastr.success(response.message, "Success", {
                            timeOut: 3000,
                            progressBar: true
                        });
                        $('#payment-options-table').DataTable().ajax.reload(); // ✅ Refresh DataTable
                        resetEditValidation(); // ✅ Clear validation errors after successful update
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.error;

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            toastr.error(errors[0], "Error", {
                                timeOut: 3000,
                                progressBar: true
                            });
                        } else {
                            toastr.error("Failed to add payment type.", "Error", {
                                timeOut: 3000,
                                progressBar: true
                            });
                        }

                        // ✅ Reset previous errors
                        resetEditValidation();

                        // ✅ Show validation errors dynamically
                        if (errors.payment_type_name) {
                            $('#editPaymentTypeName').addClass('is-invalid');
                            $('#edit_payment_type_name_error').text(errors.payment_type_name[
                                0]);
                        }

                        if (errors.status) {
                            $('#editStatus').addClass('is-invalid');
                            $('#edit_status_error').text(errors.status[0]);
                        }

                        if (errors.payment_image) {
                            $('#editPaymentImage').addClass('is-invalid');
                            $('#edit_payment_image_error').text(errors.payment_image[0]);
                        }
                    }
                });
            });

            // ✅ Function to Clear Validation Errors for Edit Form
            function resetEditValidation() {
                $('.form-text.text-danger').text(''); // ✅ Clear error messages
                $('.is-invalid').removeClass('is-invalid'); // ✅ Remove invalid class
            }

            document.getElementById('editPaymentImage').addEventListener('change', function(event) {
                let file = event.target.files[0];

                if (file) {
                    let reader = new FileReader();

                    reader.onload = function(e) {
                        let imagePreview = document.getElementById('editImagePreview');
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block'; // ✅ Show updated preview
                    };

                    reader.readAsDataURL(file);
                }
            });

        });
    </script>
@endpush
