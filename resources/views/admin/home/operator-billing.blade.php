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

        <div class="row ">
            <div class="col-md-12">
                <div class="card-hover-shadow-2x mb-3 card" id="todo-container">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                                class="fa fa-tasks"></i>&nbsp;Your Onsite transactions
                        </div>
                    </div>
                    <ul class=" list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="todo-indicator bg-primary"></div>
                            <div class="widget-content p-0">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left mr-2">
                                        <div class="custom-checkbox custom-control"><input class="custom-control-input"
                                                id="select_all" type="checkbox"><label class="custom-control-label"
                                                for="select_all">&nbsp;</label></div>
                                    </div>
                                    <div class="widget-content-left flex2">
                                        <div class="widget-heading" style="font-weight: bold; font-size: 16px">
                                            Select All
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="scroll-area-sm">
                        <perfect-scrollbar class="ps-show-limits">
                            <div style="position: static;" class="ps ps--active-y">
                                <div class="ps-content">
                                    <ul class=" list-group list-group-flush">

                                        @if ($transactions->count() > 0)
                                            @foreach ($transactions as $item)
                                                @php
                                                    $visitDate = \Carbon\Carbon::parse($item->visit_date);
                                                    $dayType = $visitDate->isWeekend() ? 'weekend' : 'weekday';
                                                @endphp
                                                <li class="list-group-item">
                                                    <div class="todo-indicator bg-primary"></div>
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left mr-2">
                                                                <div class="custom-checkbox custom-control"><input
                                                                        class="custom-control-input"
                                                                        id="{{ $item->id }}" type="checkbox"><label
                                                                        class="custom-control-label"
                                                                        for="{{ $item->id }}">&nbsp;</label></div>
                                                            </div>
                                                            <div class="widget-content-left flex2">
                                                                <input type="hidden" id="price" name="price"
                                                                    value="{{ (int) number_format($item->total_price, 0, ',', '') }}">
                                                                <div class="widget-heading"
                                                                    style="font-weight: bold; font-size: 16px">
                                                                    Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                                                </div>
                                                                <div class="widget-subheading">
                                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}
                                                                    <a href="{{ url('/download/ticket/baru/'.Crypt::encrypt($item->id)) }}" class="btn btn-info btn-sm">Ticket <i class="fa fa-download"></i></a>
                                                                </div>
                                                            </div>

                                                            <div class="widget-content-right">
                                                                @foreach ($item->groupedDetails as $detail)
                                                                    <div class="widget-subheading">
                                                                        {{ ucwords($detail->guest_type_name) }} -
                                                                        {{ $detail->total_qty }} people
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif

                                    </ul>
                                </div>

                            </div>
                        </perfect-scrollbar>
                    </div>
                    <div class="d-block text-center card-footer">
                        Selected 0 total price Rp 0
                    </div>
                    <div class="d-block text-right card-footer">
                        <button class="btn btn-block btn-primary" id="create-billing-btn">Create Billing</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.custom-control-input').prop('checked', false);

                $('.custom-control-input').on('change', function() {
                    var totalSelected = $('.custom-control-input:checked').not('#select_all')
                    .length; // ✅ Excludes "Select All"
                    var totalPrice = 0;

                    $('.custom-control-input:checked').not('#select_all').each(
                function() { // ✅ Excludes "Select All"
                        var priceElement = $(this).closest('.list-group-item').find('#price');
                        if (priceElement
                            .length) { // ✅ Ensure price element exists before accessing `.val()`
                            totalPrice += parseFloat(priceElement.val().replace(/[^\d]/g, ''));
                        }
                    });

                    $('.card-footer.text-center').html(
                        `Selected ${totalSelected} total price Rp ${totalPrice.toLocaleString()}`);
                });

                // ✅ Handle "Select All" functionality
                $('#select_all').on('change', function() {
                    var isChecked = $(this).prop('checked');
                    $('.custom-control-input').not('#select_all').prop('checked', isChecked).trigger('change');
                });

                $('#create-billing-btn').on('click', function() {
                    var selectedTransactions = [];

                    $('.custom-control-input:checked').not('#select_all').each(function() {
                        selectedTransactions.push($(this).attr('id'));
                    });

                    if (selectedTransactions.length > 0) {
                        // ✅ Show confirmation dialog before executing
                        Swal.fire({
                            title: "Are you sure?",
                            text: "Do you want to create a billing for these transactions?",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonText: "Yes, create!",
                            cancelButtonText: "Cancel"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // ✅ Show loading indicator while processing
                                Swal.fire({
                                    title: "Processing...",
                                    text: "Please wait while the billing is created.",
                                    icon: "info",
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });

                                $.ajax({
                                    url: "{{ route('operator.createBilling') }}",
                                    type: "POST",
                                    data: {
                                        selected_transactions: selectedTransactions,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            title: "Success!",
                                            text: "Billing created: " + response.billing_number +
                                                "\nTotal Orders: " + response.total_ticket_order +
                                                "\nTotal Amount: Rp " + response.total_amount,
                                            icon: "success",
                                            confirmButtonText: "OK"
                                        }).then(() => {
                                            location.reload(); // ✅ Reload page after confirmation
                                        });
                                    },
                                    error: function(xhr) {
                                        Swal.fire({
                                            title: "Error!",
                                            text: "Failed to create billing!",
                                            icon: "error",
                                            confirmButtonText: "OK"
                                        });
                                    }
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            text: "Please select at least one transaction!",
                            icon: "warning",
                            confirmButtonText: "OK"
                        });
                    }
                });






            });
        </script>
    @endpush
