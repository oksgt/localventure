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
                        <h4 class="card-title">Data Master Wisata</h4>
                        <p class="card-description d-none">
                            Add class <code>.table-striped</code>
                        </p>

                        @if (session('role_id') !== 3)
                            <a type="button" class="btn btn-sm btn-primary" id="add-destination-btn">Add New</a>
                        @endif

                        <button type="button" class="btn btn-sm btn-light" id="refresh-destination-btn">Refresh</button>

                        <div class="table-responsive mt-2">
                            <table class="table table-striped" id="destinations-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Gallery</th>
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

    <div class="modal fade" id="formDestination" tabindex="-1" aria-labelledby="formDestinationLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formDestinationLabel">Add New Destination</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form class="forms-sample" id="destinationForm">
                                        @csrf

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Destination Name</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        placeholder="Enter destination name">
                                                    <small class="form-text text-danger" id="name_error"></small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea class="form-control" id="description" name="description" placeholder="Describe the destination"></textarea>
                                                    <small class="form-text text-danger" id="description_error"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="available">Available</label>
                                                    <select class="form-control" id="available" name="available">
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                    <small class="form-text text-danger" id="available_error"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <textarea class="form-control" id="address" name="address" placeholder="Enter address" rows="3"></textarea>
                                                    <small class="form-text text-danger" id="address_error"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="latlon">Latitude, Longitude</label>
                                                    <input readonly type="text" class="form-control" id="latlon"
                                                        name="latlon" placeholder="Enter coordinates (lat, lon)">
                                                    <small class="form-text text-danger" id="latlon_error"></small>
                                                </div>
                                                <span class="text-muted" style="font-style: italic"><i
                                                        class="ti-info"></i>Drag the blue marker below to get the latitude
                                                    and longitude</span>
                                                <div id="map" style="height: 300px; border-radius: 5px;"></div>
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
                    <button type="button" class="btn btn-primary" id="btn-save-destination">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageUploadModal" tabindex="-1" aria-labelledby="imageUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageUploadModalLabel">Upload Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="imageUploadForm">
                        @csrf
                        <input type="hidden" name="destination_id" id="destination_id">

                        <div class="form-group">
                            <label for="image">Select Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept=".jpg, .jpeg, .png">
                            <small class="form-text text-danger" id="image_error"></small>
                        </div>

                        <div class="form-group text-center">
                            <img id="imagePreview" src="#" class="img-fluid rounded d-none" style="max-width: 100%; max-height: 300px;">
                            <p id="noImageText" class="text-muted">No image available</p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger d-none" id="btn-remove-image">Remove Image</button>
                            <button type="button" class="btn btn-primary" id="btn-upload-image">Upload Image</button>
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
        $(document).ready(function() {

            let map;

            $('#add-destination-btn').click(function() {

                initializeMap();
                $('#destinationForm')[0].reset();
                $('#formDestinationLabel').text("Add New Destination");

                $('#formDestination').modal('show');
            });

            $(document).on('click', '.edit-destination', function() {
                let id = $(this).data('id');

                $.ajax({
                    url: "{{ url('/admin/destinations') }}/" + id + "/edit",
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            if (response.success && response.data.latlon) {
                                initializeMap(response.data.latlon.split(',')[0], response.data.latlon.split(',')[1]); // Pass lat/lon
                            } else {
                                initializeMap(); // Default to Indonesia if no data is found
                            }

                            $('#formDestinationLabel').text("Edit Destination");
                            $('#formDestination').modal('show'); // Open modal
                            $('#name').val(response.data.name);
                            $('#description').val(response.data.description);
                            $('#address').val(response.data.address);
                            $('#latlon').val(response.data.latlon);
                            $('#available').val(response.data.available);
                            $('#btn-save-destination').attr('data-id',id); // Store ID for update
                        } else {
                            toastr.error("Failed to load destination data", "Error", {
                                timeOut: 3000,
                                progressBar: true
                            });
                        }
                    },
                    error: function() {
                        toastr.error("Failed to fetch destination details", "Error", {
                            timeOut: 3000,
                            progressBar: true
                        });
                    }
                });
            });


            $('#destinations-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.destinations.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'gallery',
                        name: 'gallery',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'location',
                        name: 'location',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#refresh-destination-btn').click(function() {
                $('#destinations-table').DataTable().ajax.reload();
            });

            $('#btn-save-destination').click(function() {
                let id = $(this).attr('data-id'); // Check if an ID exists (for update)
                let formData = {
                    _token: $('input[name="_token"]').val(),
                    _method: id ? "PUT" : "POST", // Change method dynamically
                    name: $('#name').val(),
                    description: $('#description').val(),
                    address: $('#address').val(),
                    latlon: $('#latlon').val(),
                    available: $('#available').val(),
                };

                let url = id ? "{{ url('/admin/destinations') }}/" + id + "/update" :
                    "{{ route('admin.destinations.store') }}";

                $('#btn-save-destination').prop('disabled', true).text('Processing...');

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, "Success", {
                                timeOut: 3000,
                                progressBar: true
                            });
                            $('#formDestination').modal('hide'); // Close modal
                            $('#destinations-table').DataTable().ajax
                        .reload(); // Refresh DataTable
                        } else {
                            toastr.error(response.message, "Error", {
                                timeOut: 3000,
                                progressBar: true
                            });
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Failed to process destination", "Error", {
                            timeOut: 3000,
                            progressBar: true
                        });
                    },
                    complete: function() {
                        $('#btn-save-destination').prop('disabled', false).text('Save');
                    }
                });
            });

            $(document).on('click', '.delete-destination', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This destination will be deleted.",
                    // icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('/admin/destinations') }}/" + id,
                            type: "DELETE",
                            data: {
                                _token: $('input[name="_token"]').val()
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message, "Success", { timeOut: 3000, progressBar: true });
                                    $('#destinations-table').DataTable().ajax.reload(); // Refresh DataTable
                                } else {
                                    toastr.error(response.message, "Error", { timeOut: 3000, progressBar: true });
                                }
                            },
                            error: function(xhr) {
                                toastr.error("Failed to delete destination", "Error", { timeOut: 3000, progressBar: true });
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.upload-gallery-btn', function() {
                let destinationId = $(this).data('id');

                $('#destination_id').val(destinationId); // Set destination ID in hidden input

                // Fetch existing gallery data
                $.ajax({
                    url: "{{ url('/admin/destination-gallery') }}/" + destinationId,
                    type: "GET",
                    success: function(response) {
                        if (response.success && response.image_url) {
                            $('#imagePreview').attr('src', response.image_url).removeClass('d-none');
                            $('#noImageText').addClass('d-none');
                            $('#btn-remove-image').attr('data-id', response.gallery_id).removeClass('d-none'); // Show remove button
                        } else {
                            $('#imagePreview').addClass('d-none').attr('src', '');
                            $('#noImageText').removeClass('d-none');
                            $('#btn-remove-image').addClass('d-none').attr('data-id', '');
                        }

                        $('#imageUploadModal').modal('show'); // Open modal after data is loaded
                    },
                    error: function() {
                        $('#imagePreview').addClass('d-none').attr('src', '');
                        $('#noImageText').removeClass('d-none');
                        $('#btn-remove-image').addClass('d-none').attr('data-id', '');
                        $('#imageUploadModal').modal('show'); // Open modal even if data fails to load
                    }
                });
            });

            // Preview selected image
            $('#image').change(function() {
                let file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#imagePreview').attr('src', event.target.result).removeClass('d-none');
                        $('#noImageText').addClass('d-none');
                        $('#btn-remove-image').removeClass('d-none'); // Show remove button
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Upload image
            $('#btn-upload-image').click(function() {
                let formData = new FormData($('#imageUploadForm')[0]);

                $.ajax({
                    url: "{{ route('admin.destination-gallery.upload') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, "Success", { timeOut: 3000, progressBar: true });

                            // Hide modal
                            $('#imageUploadModal').modal('hide');

                            // Reset form fields
                            $('#imageUploadForm')[0].reset();

                            // Reset preview and text
                            $('#imagePreview').attr('src', '').addClass('d-none');
                            $('#noImageText').removeClass('d-none');
                            $('#btn-remove-image').addClass('d-none').attr('data-id', '');
                        } else {
                            toastr.error(response.message, "Error", { timeOut: 3000, progressBar: true });
                        }
                    }
                });
            });

            // Remove image
            $('#btn-remove-image').click(function() {
                let id = $(this).data('id');

                $.ajax({
                    url: "{{ url('/admin/destination-gallery') }}/" + id + "/remove",
                    type: "DELETE",
                    data: { _token: $('input[name="_token"]').val() },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, "Success", { timeOut: 3000, progressBar: true });
                            $('#imagePreview').addClass('d-none').attr('src', '');
                            $('#noImageText').removeClass('d-none');
                            $('#btn-remove-image').addClass('d-none');
                        }
                    }
                });
            });


        });

        function initializeMap(lat = -2.5, lon = 117.5) {

            if (map && map instanceof L.Map) {
                map.setView([lat, lon], 5); // If map exists, just update view
                return;
            }


            // Initialize map only if it does not exist
            map = L.map('map').setView([lat, lon], 5);


            let marker = L.marker([lat, lon], { draggable: true }).addTo(map);

            // Load map tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Set initial input field value
            $('#latlon').val(lat + ', ' + lon);

            // Try to locate user's position
            map.locate({ setView: true, maxZoom: 13 });

            map.on('locationfound', function(e) {
                let userLat = e.latitude;
                let userLon = e.longitude;

                map.setView([userLat, userLon], 13); // Center map on user's location
                marker.setLatLng([userLat, userLon]); // Move marker
                $('#latlon').val(userLat + ', ' + userLon);
            });

            // Handle geolocation errors
            map.on('locationerror', function() {
                toastr.error("Failed to get your location", "Error", { timeOut: 3000, progressBar: true });
            });

            // Update input field when marker is dragged
            marker.on('dragend', function(event) {
                let newLatLng = event.target.getLatLng();
                $('#latlon').val(newLatLng.lat + ', ' + newLatLng.lng);
                map.setView(newLatLng);
            });
        }
    </script>
@endpush
