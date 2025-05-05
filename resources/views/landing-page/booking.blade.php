<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Booking Ticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="colorlib.com">
    <!-- LINEARICONS -->
    <link rel="stylesheet" href="{{ asset('booking') }}/fonts/linearicons/style.css">

    <!-- MATERIAL DESIGN ICONIC FONT -->
    <link rel="stylesheet"
        href="{{ asset('booking') }}/fonts/material-design-iconic-font/css/material-design-iconic-font.css">

    <!-- DATE-PICKER -->
    <link rel="stylesheet" href="{{ asset('booking') }}/vendor/date-picker/css/datepicker.min.css">

    <!-- STYLE CSS -->
    <link rel="stylesheet" href="{{ asset('booking') }}/css/style.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
</head>

<body>
    <div class="wrapper">
        <div class="inner">
            <div class="image-holder">
                <img id="selectedImage" src="{{ $selectedImage }}" alt="">
                <h3>ticket reservation</h3>
            </div>
            <div id="wizard">
                <!-- SECTION 1 -->
                <h4>Date & Destination</h4>
                <section>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="select w-100">
                                <div class="form-holder">
                                    <div class="select-control form-control pl-85">{{ $selectedDestinationName }}</div>
                                    <span class="lnr lnr-chevron-down"></span>
                                    <span class="placeholder">Destination:</span>
                                </div>
                                <ul id="selectDestination" class="dropdown">
                                    @foreach ($destinations as $destination)
                                        <li rel="{{ $destination->name }}" data-id="{{ $destination->id }}"
                                            data-image="{{ asset('storage/destination/' . basename($destination->images->first()->image_url)) }}"
                                            class="destination-item">
                                            {{ $destination->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder w-100">
                            <input type="text" class="form-control datepicker-here pl-85" data-language='en'
                                data-date-format="dd - M - yyyy" id="dp1">
                            <span class="lnr lnr-chevron-down"></span>
                            <span class="placeholder">Date:</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder w-100">
                            <input type="text" class="form-control pl-85" value="{{ $searchData['people_count'] }}">
                            <span class="placeholder">People:</span>
                        </div>
                    </div>

                    <button class="forward">NEXT
                        <i class="zmdi zmdi-long-arrow-right"></i>
                    </button>
                </section>

                <h4>Reservation Data</h4>
                <section>
                    <div class="form-row">
                        <div class="form-holder w-100">
                            <input type="text" class="form-control pl-85" name="name">
                            <span class="placeholder">Name:</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-holder w-100">
                            <input type="text" class="form-control pl-85" name="address">
                            <span class="placeholder">Address:</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="select w-100">
                                <div class="form-holder">
                                    <input type="text" id="provinceSearch" class="form-control pl-85" placeholder="Search province...">
                                    <span class="placeholder">Province:</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="select w-100">
                                <div class="form-holder">
                                    <input type="text" id="regencySearch" class="form-control pl-85" placeholder="Search regency..." disabled>
                                    <span class="placeholder">Regency:</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="select w-100">
                                <div class="form-holder">
                                    <input type="text" id="districtSearch" class="form-control pl-85" placeholder="Search district..." disabled>
                                    <span class="placeholder">District:</span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-holder w-100">
                            <input type="text" class="form-control pl-85" name="phone">
                            <span class="placeholder">Phone:</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-holder w-100">
                            <input type="email" class="form-control pl-85" name="email">
                            <span class="placeholder">Email:</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-holder w-100">
                            <textarea type="text" class="form-control pl-85" name="origin" style="height: 80px;"></textarea>
                            <span class="placeholder" style="position: absolute; top: 0px;">Origin:</span>
                        </div>
                    </div>
                    <button class="forward" style="width: 195px; margin-top: 44px;">Book by email
                        <i class="zmdi zmdi-long-arrow-right"></i>
                    </button>
                </section>

                <h4>Ticket Option</h4>
                <section>

                    @if ($pricing->isNotEmpty())
                        <div class="board-wrapper" style="margin-bottom: 30px;">
                            @foreach (['weekday', 'weekend'] as $dayType)
                                <div class="board-inner">
                                    <div class="board-item" style="font-weight: 600;">
                                        {{ ucfirst($dayType) }} :
                                    </div>
                                    <div class="board-line">
                                        @foreach ($pricing->where('day_type', $dayType) as $price)
                                            <div class="board-item">
                                                {{ $price->guest_name }} :
                                                <br>
                                                <span>Rp. {{ number_format($price->final_price, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-row">
                            <div class="form-holder w-100 ">
                                <input type="number" class="form-control pl-85" value="0">
                                <span class="placeholder">Kids:</span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-holder w-100 ">
                                <input type="number" class="form-control pl-85" value="0">
                                <span class="placeholder">Adults:</span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-holder w-100 ">
                                <input type="number" class="form-control pl-85" value="0">
                                <span class="placeholder">Foreigners:</span>
                            </div>
                        </div>
                        <button class="forward" style="width: 195px; margin-top: 44px;">Book by email
                            <i class="zmdi zmdi-long-arrow-right"></i>
                        </button>

                    @else
                        <div class="board-wrapper" style="margin-bottom: 30px;">
                            <div class="board-inner">
                                <div class="board-item" style="font-weight: 600;">
                                    Tickets are not available.
                                </div>
                            </div>
                        </div>
                    @endif
                </section>


                @if ($pricing->isNotEmpty())
                    <h4>Confirmation</h4>
                    <section class="section-style">
                        <div class="board-wrapper">
                            <div class="board-inner">
                                <div class="board-item">
                                    Check In :
                                    <span>2-5-2018</span>
                                </div>
                                <div class="board-item">
                                    Check Out :
                                    <span>8-5-2018</span>
                                </div>
                            </div>
                        </div>
                        <div class="pay-wrapper">
                            <div class="bill">
                                <div class="bill-cell">
                                    <div class="bill-item">
                                        <div class="bill-unit">
                                            Room 1 : <span>Small Room</span>
                                        </div>
                                        <span class="price">$34</span>
                                    </div>
                                    <div class="bill-item people">
                                        <div class="bill-unit">
                                            Adult : <span>2</span>
                                        </div>
                                        <div class="bill-unit">
                                            Children : <span>0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bill-cell" style="margin-bottom: 13px">
                                    <div class="bill-item">
                                        <div class="bill-unit">
                                            Room 2 : <span>Luxury Room</span>
                                        </div>
                                        <span class="price">$23</span>
                                    </div>
                                    <div class="bill-item people">
                                        <div class="bill-unit">
                                            Adult : <span>4</span>
                                        </div>
                                        <div class="bill-unit">
                                            Children : <span>1</span>
                                        </div>
                                    </div>
                                    <div class="bill-item service">
                                        <div class="bill-unit">
                                            Rooms & Services :
                                        </div>
                                        <span class="price">$80</span>
                                    </div>
                                </div>
                                <div class="bill-cell">
                                    <div class="bill-item vat">
                                        <div class="bill-unit">
                                            Vat 8% :
                                        </div>
                                        <span class="price">$08</span>
                                    </div>
                                    <div class="bill-item total-price">
                                        <div class="bill-unit">
                                            Total Price :
                                        </div>
                                        <span class="price">$88</span>
                                    </div>
                                    <div class="checkbox-circle">
                                        <label>
                                            <input type="radio" name="payment" value="full payment" checked> Full
                                            Payment<br>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label>
                                            <input type="radio" name="payment" value="10% Deposit"> 10% Deposit
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="bill-item total">
                                        <div class="bill-unit">
                                            <span>20% Deposit</span>
                                            Pay the rest on arrival
                                        </div>
                                        <span class="price">$78</span>
                                    </div>
                                </div>
                            </div>
                            <button style="width: 195px; margin-top: 45px;">Confirmation
                                <i class="zmdi zmdi-long-arrow-right"></i>
                            </button>
                        </div>
                    </section>

                @else

                @endif

            </div>
        </div>
    </div>

    <script src="{{ asset('booking') }}/js/jquery-3.3.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <!-- JQUERY STEP -->
    <script src="{{ asset('booking') }}/js/jquery.steps.js"></script>

    <!-- DATE-PICKER -->
    <script src="{{ asset('booking') }}/vendor/date-picker/js/datepicker.js"></script>
    <script src="{{ asset('booking') }}/vendor/date-picker/js/datepicker.en.js"></script>


    <script src="{{ asset('booking') }}/js/main.js"></script>
    <!-- Template created and distributed by Colorlib -->
    <script>
        $(document).ready(function() {
            var dp1 = $('#dp1').datepicker().data('datepicker');
            dp1.selectDate(new Date());

            $('.destination-item').on('click', function() {
                let imageUrl = $(this).data('image'); // ✅ Get stored image URL

                $('#selectedImage').fadeOut(200, function() { // ✅ Smooth fade effect
                    $(this).attr('src', imageUrl).fadeIn(200);
                });
            });

            let dateFromServer = "{{ $searchData['daterange'] ?? '' }}"; // ✅ Get date from backend

            if (dateFromServer) {
                let formattedDate = new Date(dateFromServer); // ✅ Convert string to Date object
                let dp1 = $('#dp1').datepicker().data('datepicker');

                dp1.selectDate(formattedDate); // ✅ Set datepicker value dynamically
            }

            $("#provinceSearch").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "{{ route('get.provinces') }}",
                        type: "GET",
                        data: { search: request.term },
                        success: function (data) {
                            response($.map(data, function (item) {
                                return {
                                    label: item.name,
                                    value: item.name,
                                    id: item.id
                                };
                            }));
                        }
                    });
                },
                select: function (event, ui) {
                    $('#regencySearch').prop('disabled', false).val(''); // ✅ Enable regency search
                    $('#districtSearch').prop('disabled', true).val(''); // ✅ Reset district input

                    loadRegencies(ui.item.id);
                },
                minLength: 1
            });

            function loadRegencies(provinceId) {
                $("#regencySearch").autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: "{{ route('get.regencies') }}",
                            type: "GET",
                            data: { province_id: provinceId, search: request.term },
                            success: function (data) {
                                response($.map(data, function (item) {
                                    return {
                                        label: item.name,
                                        value: item.name,
                                        id: item.id
                                    };
                                }));
                            }
                        });
                    },
                    select: function (event, ui) {
                        $('#districtSearch').prop('disabled', false).val(''); // ✅ Enable district search
                        loadDistricts(ui.item.id);
                    },
                    minLength: 1
                });
            }

            function loadDistricts(regencyId) {
                $("#districtSearch").autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: "{{ route('get.districts') }}",
                            type: "GET",
                            data: { regency_id: regencyId, search: request.term },
                            success: function (data) {
                                response($.map(data, function (item) {
                                    return {
                                        label: item.name,
                                        value: item.name
                                    };
                                }));
                            }
                        });
                    },
                    minLength: 1
                });
            }

            document.querySelector('input[type="number"]').addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value < 1) this.value = 0; // Ensures minimum value of 1
            });


        });
    </script>
</body>

</html>
