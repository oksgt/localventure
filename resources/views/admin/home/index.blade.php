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
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title mb-4">Visitor Graphics</p>
                        {{-- <input type="text" id="daterange" class="form-control mb-4" readonly /> --}}

                        <div class="form-group">
                            <div class="input-group">
                            <input type="text" id="daterange" class="form-control mb-4" readonly style="cursor: pointer; background: white">
                        </div>

                        <canvas id="lineChart" height="100"></canvas>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
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

            function fetchData(startDate, endDate) {
                $.ajax({
                    url: '{{ route('admin.visitor.chart.data') }}', // Define your route
                    method: 'GET',
                    data: {
                        start: startDate,
                        end: endDate
                    },
                    success: function(data) {
                        if (chart) {
                            chart.destroy(); // Destroy previous chart instance
                        }

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
                            // options: {
                            //     responsive: true,
                            //     scales: {
                            //         y: {
                            //             beginAtZero: true
                            //         }
                            //     }
                            // }
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
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            // Initial fetch
            // fetchData();
            // Fetch data every 1 minute
            // setInterval(fetchData, 60000);
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
                fetchData(initialStart.format('YYYY-MM-DD'), initialEnd.format('YYYY-MM-DD'));
            });
        });
    </script>
@endpush
