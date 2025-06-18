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

        @if ($destinations->count() == 0)
            @if (session('role_id') == 1)
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Oups!</strong> There is nothing to show you right now.
                </div>
            @else
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Oups!</strong> It looks like you haven't mapped to any destinations yet
                </div>
            @endif
        @else
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Filter data</h4>
                            <form class="form-inline mb-4">
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="daterange">Periode:</label>
                                    <input type="text" id="daterange" class="form-control my-1 mr-sm-2" readonly
                                        style="cursor: pointer; background: white">
                                </div>

                                <div class="form-group mx-sm-3">
                                    <label class="my-1 mr-2" for="destinationSelect">Destination:</label>
                                    <select class="form-control my-1" id="destinationSelect">
                                        @foreach ($destinations as $item)
                                            <option selected value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="button" class="btn btn-primary my-1" id="filterButton">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title mb-4 line-chart-title">Visitor Graphics</p>
                            <canvas id="lineChart" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card position-relative">
                        <div class="card-body">
                            <div id="detailedReports" class="carousel slide detailed-report-carousel position-static pt-2"
                                data-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <div class="row">
                                            <div class="col-md-4 d-flex flex-column justify-content-start mb-3">
                                                <div class="ml-xl-4 mt-3">
                                                    <p class="card-title summary-report-title">Summary Reports</p>
                                                    <h2 class="text-primary" id="totalRevenueLabel">Rp. 121.000.000</h2>
                                                    <h5 class="font-weight-500 mb-xl-4 text-primary" id="selectedPeriod">
                                                        Periode 18-06-2021</h5>
                                                    <p class="mb-2 mb-xl-0">
                                                        Description: Total pendapatan pada periode diatas
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6 border-right">
                                                        <div class="table-responsive mb-3 mb-md-0">
                                                            <table class="table table-borderless report-table"
                                                                id="visitorTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Category</th>
                                                                        <th>Qty</th>
                                                                        <th>Revenue</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-muted">Anak-anak</td>
                                                                        <td>
                                                                            <h5 class="font-weight-bold mb-0">713</h5>
                                                                        </td>
                                                                        <td>
                                                                            <h5 class="font-weight-bold mb-0">Rp. 100.000
                                                                            </h5>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-muted">Dewasa</td>
                                                                        <td>
                                                                            <h5 class="font-weight-bold mb-0">713</h5>
                                                                        </td>
                                                                        <td>
                                                                            <h5 class="font-weight-bold mb-0">Rp. 100.000
                                                                            </h5>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-muted">Mancanegara</td>
                                                                        <td>
                                                                            <h5 class="font-weight-bold mb-0">713</h5>
                                                                        </td>
                                                                        <td>
                                                                            <h5 class="font-weight-bold mb-0">Rp. 100.000
                                                                            </h5>
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-3">
                                                        <div class="chartjs-size-monitor">
                                                            <div class="chartjs-size-monitor-expand">
                                                                <div class=""></div>
                                                            </div>
                                                            <div class="chartjs-size-monitor-shrink">
                                                                <div class=""></div>
                                                            </div>
                                                        </div>
                                                        <canvas id="purchasingSalesChart"
                                                            style="display: block; height: 195px; width: 390px;"
                                                            width="780" height="390"
                                                            class="chartjs-render-monitor"></canvas>
                                                        <div id="pieChartLegend">
                                                            <div class="report-chart">
                                                                <div
                                                                    class="d-flex justify-content-between mx-4 mx-xl-5 mt-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="mr-3"
                                                                            style="width:20px; height:20px; border-radius: 50%; background-color: #4B49AC">
                                                                        </div>
                                                                        <p class="mb-0">Offline sales</p>
                                                                    </div>
                                                                    <p class="mb-0">88333</p>
                                                                </div>
                                                                <div
                                                                    class="d-flex justify-content-between mx-4 mx-xl-5 mt-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="mr-3"
                                                                            style="width:20px; height:20px; border-radius: 50%; background-color: #FFC100">
                                                                        </div>
                                                                        <p class="mb-0">Online sales</p>
                                                                    </div>
                                                                    <p class="mb-0">66093</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif


    @endsection

    @push('scripts')
        <script src="{{ asset('admin-page') }}/vendors/select2/select2.min.js"></script>
        <script>
            $(document).ready(function() {

                // Set CSRF token for AJAX requests
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let chart;
                const ctx = document.getElementById('lineChart').getContext('2d');

                let doughnutChart;
                const ctxDoughnut = document.getElementById('purchasingSalesChart').getContext('2d');

                function fetchData(startDate, endDate) {
                    $.ajax({
                        url: '{{ route('admin.visitor.chart.data') }}', // Define your route
                        method: 'GET',
                        data: {
                            start: startDate,
                            end: endDate,
                            destination_id: $('#destinationSelect').val()
                        },
                        dataType: 'json',
                        error: function(xhr, status, error) {
                            console.error(error);
                        },
                        success: function(response) {
                            if (chart) {
                                chart.destroy(); // Destroy previous chart instance
                            }

                            if (doughnutChart) {
                                doughnutChart.destroy(); // Destroy previous chart instance
                            }

                            $('#visitorTable tbody').empty();

                            // Loop through visitorDetail data and append to the table
                            response.visitorDetail.forEach(function(item) {
                                $('#visitorTable tbody').append(`
                                    <tr>
                                        <td class="text-muted">${item.name}</td>
                                        <td>
                                            <h5 class="font-weight-bold mb-0">${item.qty}</h5>
                                        </td>
                                        <td>
                                            <h5 class="font-weight-bold mb-0">Rp. ${parseFloat(item.total_price).toLocaleString()}</h5>
                                        </td>
                                    </tr>
                                `);
                            });

                            $('.line-chart-title').text('Visitor Graphics ' + response.destinations);
                            $('.summary-report-title').text('Summary Reports ' + response.destinations);
                            $('#totalRevenueLabel').text('Rp. ' + response.totalRevenue.toLocaleString());
                            $('#selectedPeriod').text('Periode ' + response.selectedPeriod);

                            const data = response.visitorCount; // Adjusted to access the visitorCount

                            const labels = data.map(item => item.visit_date);
                            const qtyData = data.map(item => item.qty);

                            chart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Visitor Count',
                                        data: qtyData,
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1,
                                        fill: false,
                                        tension: 0.4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        title: {
                                            display: false,
                                            text: 'Visitor Graphics'
                                        },
                                    },
                                    interaction: {
                                        intersect: false,
                                    },
                                    scales: {
                                        x: {
                                            display: true,
                                            title: {
                                                display: true
                                            }
                                        },
                                        y: {
                                            display: true,
                                            title: {
                                                display: true,
                                                text: 'Value'
                                            },
                                            suggestedMin: -10,
                                            suggestedMax: 200
                                        }
                                    }
                                },
                            });

                            const dataPurchasingSalesType = response.PurchasingSalesPieChart
                            .data; // Adjusted to access the visitorCount

                            const labelsPCS = dataPurchasingSalesType.map(item => item.purchasing_type);
                            const qtyDataPCS = dataPurchasingSalesType.map(item => item.total);

                            console.log(dataPurchasingSalesType);
                            console.log(labelsPCS);
                            console.log(qtyDataPCS);

                            doughnutChart = new Chart(ctxDoughnut, {
                                type: 'doughnut',
                                data: {
                                    labels: labelsPCS,
                                    datasets: [{
                                        label: 'Total Purchase Amount',
                                        data: qtyDataPCS,
                                        backgroundColor: [
                                            'rgb(75 73 172)',
                                            'rgb(255 193 0)'
                                        ],
                                        hoverOffset: 4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        title: {
                                            display: false,
                                            text: 'Puchasing Sales Type'
                                        },
                                    },

                                },
                            });


                            // Clear existing content
                            const chartContainer = document.getElementById('pieChartLegend');
                            chartContainer.innerHTML = '';

                            response.PurchasingSalesPieChart.data.forEach(item => {
                                const salesType = item.purchasing_type.charAt(0).toUpperCase() +
                                    item.purchasing_type.slice(1);
                                const color = salesType === 'Online' ? '#4b49ac' :
                                '#FFC100'; // Different colors for types

                                const salesItemHTML = `
                                    <div class="d-flex justify-content-between mx-4 mx-xl-5 mt-3">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3" style="width:20px; height:20px; border-radius: 50%; background-color: ${color};"></div>
                                            <p class="mb-0">${salesType} sales</p>
                                        </div>
                                        <p class="mb-0">Rp. ${parseFloat(item.total).toLocaleString()}</p>
                                    </div>
                                `;

                                chartContainer.insertAdjacentHTML('beforeend', salesItemHTML);
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                }

                $(function() {
                    // Initialize the date range picker
                    $('#daterange').daterangepicker({
                        opens: 'left',
                        locale: {
                            format: 'D MMMM YYYY'
                        }
                    }, function(start, end) {
                        fetchData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                    });

                    // Initial fetch with default date range
                    const initialStart = moment().subtract(7, 'days');
                    const initialEnd = moment();
                    $('#daterange').data('daterangepicker').setStartDate(initialStart);
                    $('#daterange').data('daterangepicker').setEndDate(initialEnd);
                    // fetchData(initialStart.format('YYYY-MM-DD'), initialEnd.format('YYYY-MM-DD'));
                });

                function submit(event) {
                    event.preventDefault();

                    // Get selected dates
                    var startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    var endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');

                    fetchData(startDate, endDate);
                }

                $('#filterButton').on('click', submit);

                const initialStart = moment().subtract(7, 'days');
                const initialEnd = moment();
                fetchData(initialStart.format('YYYY-MM-DD'), initialEnd.format('YYYY-MM-DD'));
            });
        </script>
    @endpush
