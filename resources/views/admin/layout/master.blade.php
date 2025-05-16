<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>LocalVenture</title>
    <link rel="stylesheet" href="{{ asset('admin-page') }}/vendors/feather/feather.css">
    <link rel="stylesheet" href="{{ asset('admin-page') }}/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="{{ asset('admin-page') }}/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="{{ asset('admin-page') }}/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-page') }}/js/select.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('admin-page') }}/css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />


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
    <script src="{{ asset('admin-page') }}/js/dashboard.js"></script>
    <script src="{{ asset('admin-page') }}/js/Chart.roundedBarCharts.js"></script>
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
