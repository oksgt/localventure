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

        <div class="col-12 p-0">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Purchasing Ticket</h4>

                    <div class="container p-0">
                        <!-- ✅ Ticket Pricing Table -->

                        <h3 class="mb-3 text-center">{{ $destinations->name }}</h3>

                        @foreach ($formattedPrices as $dayType => $prices)
                            <div class="alert alert-{{ $dayType == $currentDayType ? 'success' : 'secondary' }}" role="alert">
                                <h4 class="alert-heading">{{ ucfirst($dayType) }}</h4>
                                <ul class="list-group list-group-flush">
                                    @foreach ($prices as $guestType => $price)
                                        <li class="list-group-item">{{ ucfirst($guestType) }} : Rp. {{ number_format($price, 0, ',', '.') }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach

                        <!-- ✅ Ticket Selection Form -->
                        <div class="mt-4">
                            <form id="ticketForm">
                                <div class="form-group">
                                    <label for="anak">Anak-anak:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="" value="0" readonly id="anak-anak" autocomplete="off">
                                        <div class="input-group-append" id="button-addon4">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-arrow-up"></i></button>
                                            <button class="btn btn-primary" type="button"><i class="fa fa-arrow-down"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dewasa">Dewasa:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="" value="0" readonly id="dewasa" autocomplete="off">
                                        <div class="input-group-append" id="button-addon4">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-arrow-up"></i></button>
                                            <button class="btn btn-primary" type="button"><i class="fa fa-arrow-down"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="mancanegara">Mancanegara:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="" value="0" readonly id="mancanegara" autocomplete="off">
                                        <div class="input-group-append" id="button-addon4">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-arrow-up"></i></button>
                                            <button class="btn btn-primary" type="button"><i class="fa fa-arrow-down"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- ✅ Pricing Summary Table -->
                        <div class="mt-4">
                            <table class="table table-striped table-hover" id="summaryTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Anak-anak</td>
                                        <td><span id="anak-anak-quantity">0</span></td>
                                        <td>Rp <span id="anak-anak-price">0</span></td>
                                    </tr>
                                    <tr>
                                        <td>Dewasa</td>
                                        <td><span id="dewasa-quantity">0</span></td>
                                        <td>Rp <span id="dewasa-price">0</span></td>
                                    </tr>
                                    <tr>
                                        <td>Mancanegara</td>
                                        <td><span id="mancanegara-quantity">0</span></td>
                                        <td>Rp <span id="mancanegara-price">0</span></td>
                                    </tr>
                                    <tr class="table-success font-weight-bold">
                                        <td>Total</td>
                                        <td><span id="total-quantity">0</span></td>
                                        <td>Rp <span id="total-price">0</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-block btn-primary" id="btn-purchase-tickets">Purchase</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="{{ asset('admin-page') }}/vendors/select2/select2.min.js"></script>
        <script>
            var ticketPrices = JSON.parse(@json($formattedCurrentPrices));
            console.log(ticketPrices); // ✅ Should display an array of objects
        </script>


        <script>
            $(document).ready(function() {

                const currentDay = "{{ $currentDayType }}";

                $('.fa-arrow-up, .fa-arrow-down').parent().on('click', function() {
                    var inputField = $(this).closest('.input-group').find('input');
                    var currentValue = parseInt(inputField.val()) || 0;
                    var guestType = inputField.attr('id'); // ✅ Get related guest name

                    if ($(this).find('i').hasClass('fa-arrow-up')) {
                        currentValue += 1;
                    } else if (currentValue > 0) {
                        currentValue -= 1;
                    }

                    inputField.val(currentValue);

                    updateSummaryTable(guestType, currentValue);
                });

                function updateSummaryTable(guestType, qty) {
                    var finalPrice = ticketPrices.find(ticket => ticket.guest_name === guestType)?.final_price || 0;
                    var totalPrice = qty * finalPrice;

                    // ✅ Update the quantity and price in the table
                    $('#' + guestType + '-quantity').text(qty);
                    $('#' + guestType + '-price').text(totalPrice.toLocaleString('id-ID'));

                    updateTotalSummary();
                }

                function updateTotalSummary() {
                    var totalPrice = 0;
                    var totalQty = 0;

                    ['anak-anak', 'dewasa', 'mancanegara'].forEach(guestType => {
                        var qty = parseInt($('#' + guestType + '-quantity').text()) || 0;
                        var price = parseInt($('#' + guestType + '-price').text().replace(/\./g, '')) || 0;

                        totalQty += qty;
                        totalPrice += price;
                    });

                    $('#total-price').text(totalPrice.toLocaleString('id-ID'));
                    $('#total-quantity').text(totalQty);
                }

            });
        </script>

    @endpush
