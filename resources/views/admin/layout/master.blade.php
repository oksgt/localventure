<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LocalVenture</title>
    <link rel="stylesheet" href="{{ asset('admin-page') }}/vendors/feather/feather.css">
    <link rel="stylesheet" href="{{ asset('admin-page') }}/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="{{ asset('admin-page') }}/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="{{ asset('admin-page') }}/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="{{ asset('admin-page') }}/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-page') }}/js/select.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('admin-page') }}/css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

    <style>

        #todo-container .card {
            box-shadow: 0 0.46875rem 2.1875rem rgba(4, 9, 20, 0.03),
                0 0.9375rem 1.40625rem rgba(4, 9, 20, 0.03),
                0 0.25rem 0.53125rem rgba(4, 9, 20, 0.05),
                0 0.125rem 0.1875rem rgba(4, 9, 20, 0.03);
            border-width: 0;
            transition: all .2s;
        }

        #todo-container .card-header:first-child {
            border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
        }

        #todo-container .card-header {
            display: flex;
            align-items: center;
            border-bottom-width: 1px;
            padding-top: 0;
            padding-bottom: 0;
            padding-right: 0.625rem;
            height: 3.5rem;
            background-color: #fff;
        }

        #todo-container .widget-subheading {
            color: #858a8e;
            font-size: 10px;
        }

        #todo-container .btn-actions-pane-right {
            margin-left: auto;
            white-space: nowrap;
        }

        #todo-container .text-capitalize {
            text-transform: capitalize !important;
        }

        #todo-container .scroll-area-sm {
            height: 500px;
            overflow-x: hidden;
        }

        #todo-container .list-group-item {
            position: relative;
            display: block;
            padding: 0.75rem 1.25rem;
            margin-bottom: -1px;
            background-color: #fff;
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        #todo-container .list-group {
            display: flex;
            flex-direction: column;
            padding-left: 0;
            margin-bottom: 0;
        }

        #todo-container .todo-indicator {
            position: absolute;
            width: 4px;
            height: 60%;
            border-radius: 0.3rem;
            left: 0.625rem;
            top: 20%;
            opacity: .6;
            transition: opacity .2s;
        }

        #todo-container .bg-warning {
            background-color: #f7b924 !important;
        }

        #todo-container .widget-content {
            padding: 1rem;
            flex-direction: row;
            align-items: center;
        }

        #todo-container .widget-content .widget-content-wrapper {
            display: flex;
            flex: 1;
            position: relative;
            align-items: center;
        }

        #todo-container .widget-content .widget-content-right.widget-content-actions {
            visibility: hidden;
            opacity: 0;
            transition: opacity .2s;
        }

        #todo-container .widget-content .widget-content-right {
            margin-left: auto;
        }

        #todo-container .btn {
            position: relative;
            transition: color 0.15s, background-color 0.15s, border-color 0.15s, box-shadow 0.15s;
            outline: none !important;
        }

        #todo-container .btn-outline-success {
            color: #3ac47d;
            border-color: #3ac47d;
        }

        #todo-container .btn-outline-success:hover {
            color: #fff;
            background-color: #3ac47d;
            border-color: #3ac47d;
        }

        #todo-container .btn-primary {
            color: #fff;
            background-color: #3f6ad8;
            border-color: #3f6ad8;
        }

        #todo-container .card-footer {
            background-color: #fff;
        }
    </style>
    <style>
        #qr-video {
        width: 100%;
        border-radius: 0.5rem;
        border: 2px solid #dee2e6;
        }
        .result-box {
        font-family: monospace;
        word-break: break-all;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        @include('admin.partials.navbar')

        <div class="container-fluid page-body-wrapper">
            @include('admin.partials.sidebar')

            <div class="main-panel">
                @yield('content')
                @include('admin.partials.footer')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin-page') }}/vendors/js/vendor.bundle.base.js"></script>
    <script src="{{ asset('admin-page') }}/vendors/chart.js/Chart.min.js"></script>
    <script src="{{ asset('admin-page') }}/vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="{{ asset('admin-page') }}/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <script src="{{ asset('admin-page') }}/js/dataTables.select.min.js"></script>
    <script src="{{ asset('admin-page') }}/js/off-canvas.js"></script>
    <script src="{{ asset('admin-page') }}/js/hoverable-collapse.js"></script>
    <script src="{{ asset('admin-page') }}/js/template.js"></script>
    <script src="{{ asset('admin-page') }}/js/settings.js"></script>
    <script src="{{ asset('admin-page') }}/js/todolist.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
    {{-- <script src="{{ asset('admin-page') }}/js/dashboard.js"></script> --}}
    <script src="{{ asset('admin-page') }}/js/Chart.roundedBarCharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script> --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
    </script>
    @stack('scripts')
</body>

</html>
