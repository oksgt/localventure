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
    <link rel="shortcut icon" href="{{ asset('admin-page') }}/images/favicon.png" />
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
</body>

</html>
