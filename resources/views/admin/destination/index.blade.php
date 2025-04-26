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
                                        <th>Location</th>
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
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            let map = L.map('map').setView([-2.5, 117.5], 5); // Default view centered on Indonesia
            let marker = L.marker([-2.5, 117.5], { draggable: true }).addTo(map); // Default marker


            $('#add-destination-btn').click(function() {

                // Load map tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // Update input field with default position
                $('#latlon').val('-2.5, 117.5');

                // Geolocation function to update marker
                map.locate({ setView: true, maxZoom: 13 });

                map.on('locationfound', function(e) {
                    let lat = e.latitude;
                    let lon = e.longitude;

                    map.setView([lat, lon], 13); // Center map
                    marker.setLatLng([lat, lon]); // Move marker to user's location
                    $('#latlon').val(lat + ', ' + lon); // Update input value
                });

                // Handle errors if geolocation fails
                map.on('locationerror', function() {
                    toastr.error("Failed to get your location", "Error", { timeOut: 3000, progressBar: true });
                });

                // Update input field when marker is dragged
                marker.on('dragend', function(event) {
                    let newLatLng = event.target.getLatLng();
                    $('#latlon').val(newLatLng.lat + ', ' + newLatLng.lng);

                    // Ensure the map centers on the dragged marker
                    map.setView(newLatLng);
                });

                $('#formDestination').modal('show');
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
                let formData = {
                    _token: $('input[name="_token"]').val(),
                    name: $('#name').val(),
                    description: $('#description').val(),
                    address: $('#address').val(),
                    latlon: $('#latlon').val(),
                    available: $('#available').val(),
                };

                $('#btn-save-destination').prop('disabled', true).text('Processing...');

                $.ajax({
                    url: "{{ route('admin.destinations.store') }}",
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
                        toastr.error("Failed to add destination", "Error", {
                            timeOut: 3000,
                            progressBar: true
                        });
                    },
                    complete: function() {
                        $('#btn-save-destination').prop('disabled', false).text('Save');
                    }
                });
            });

        });
    </script>
@endpush
