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

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- STYLE CSS -->
    <link rel="stylesheet" href="{{ asset('booking') }}/css/style.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
</head>

<body>
    <div class="wrapper">
        <div class="inner">
            <div class="image-holder">
                <img id="selectedImage" src="{{ asset('/landing-page/images/camping.jpg') }}" alt="">
                <h3>ticket reservation</h3>
            </div>
            <div id="wizard">
                <!-- SECTION 1 -->
                <h4>Date & Ticket</h4>
                <section>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="select w-100">
                                <div class="form-holder">
                                    <div class="select-control form-control pl-85">{{ $selectedDestinationName }}</div>
                                    <input type="hidden" name="selectDestinationId" id="selectDestinationId"
                                        value="{{ $searchData['destination_id'] }}" />
                                    <span class="lnr lnr-chevron-down"></span>
                                    <span class="placeholder">Ticket for :</span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder w-100">
                            <input type="text" class="form-control datepicker-here pl-85" data-language='en'
                                data-date-format="dd - M - yyyy" id="dp1" name="date" readonly>
                            <span class="lnr lnr-chevron-down"></span>
                            <span class="placeholder">Date:</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder w-100">
                            <input type="text" class="form-control pl-85" value="{{ $searchData['people_count'] }}"
                                name="people_count" readonly>
                            <span class="placeholder">People:</span>
                        </div>
                    </div>

                    <div class="form-row" style="margin-top: 0px;">
                        <button class="forwardFirst">NEXT
                            <i class="zmdi zmdi-long-arrow-right"></i>
                        </button>
                    </div>


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
                                    <input type="text" id="provinceSearch" name="provinceSearch"
                                        class="form-control pl-85" placeholder="Search province...">
                                    <span class="placeholder">Province:</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="select w-100">
                                <div class="form-holder">
                                    <input type="text" id="regencySearch" name="regencySearch"
                                        class="form-control pl-85" placeholder="Search regency..." disabled>
                                    <span class="placeholder">Regency:</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="select w-100">
                                <div class="form-holder">
                                    <input type="text" id="districtSearch" name="districtSearch"
                                        class="form-control pl-85" placeholder="Search district..." disabled>
                                    <span class="placeholder">District:</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-holder w-100">
                            <input type="text" class="form-control pl-85" name="phone" id="phone">
                            <span class="placeholder">Phone:</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-holder w-100">
                            <input type="email" class="form-control pl-85" name="email" id="email">
                            <span class="placeholder">Email:</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-holder w-100">
                            <textarea type="text" class="form-control pl-85" name="origin" id = "origin" style="height: 80px;"></textarea>
                            <span class="placeholder" style="position: absolute; top: 0px;">Origin:</span>
                        </div>
                    </div>


                    <div class="form-row" style="margin-top: 0px;">
                        <button class="back-navigation backSecond">Back
                            <i class="zmdi zmdi-long-arrow-left"></i>
                        </button>

                        <button class="forwardSecond">NEXT
                            <i class="zmdi zmdi-long-arrow-right"></i>
                        </button>
                    </div>
                </section>

                <h4>Ticket Option</h4>
                <section>
                    <div id="pricingBoard">
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
                                                    {{ ucwords($price->guest_name) }} :
                                                    <br>
                                                    <span>Rp.
                                                        {{ number_format($price->final_price, 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @foreach ($guestTypes as $item)
                                <div class="form-row">
                                    <div class="form-holder w-100 ">
                                        <input type="number" class="form-control pl-120" min="0"
                                            value="0" name="{{ $item->name }}" id="{{ $item->id }}">
                                        <span class="placeholder">{{ ucwords($item->name) }}:</span>
                                    </div>
                                </div>
                            @endforeach

                            <div class="form-row">
                                <table style="width: 100%;" id="ticketSummaryTable">
                                    <tr>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                    </tr>

                                    @foreach ($guestTypes as $item)
                                        <tr>
                                            <td>{{ ucwords($item->name) }}</td>
                                            <td id="selectedTicket_{{ $item->id }}">x 0</td>
                                            <td id="ticketPrice_{{ $item->id }}" style="text-align: right">Rp. 0
                                            </td>
                                        </tr>
                                    @endforeach


                                    <tr style="background-color: #edc948; color: black">
                                        <td colspan="2"><strong>Total</strong></td>
                                        <td style="text-align: right"><strong>Rp. 45.000</strong></td>
                                    </tr>
                                </table>
                            </div>



                            <div class="form-row" style="margin-top: 0px;">
                                <button class="back-navigation backSecond">Back
                                    <i class="zmdi zmdi-long-arrow-left"></i>
                                </button>

                                <button class="forwardThird">NEXT
                                    <i class="zmdi zmdi-long-arrow-right"></i>
                                </button>
                            </div>
                        @else
                            <div class="board-wrapper" style="margin-bottom: 30px;">
                                <div class="board-inner">
                                    <div class="board-item" style="font-weight: 600;">
                                        Tickets are not available.
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>

                <h4>Payment Method</h4>
                <section>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="select w-100">
                                <div class="form-holder">
                                    <div class="select-control form-control pl-85">-- Please select --</div>
                                    <span class="lnr lnr-chevron-down"></span>
                                    <span class="placeholder">Payment:</span>
                                    <input type="hidden" name="selectPaymentId" id="selectPaymentId"
                                        value="" />
                                </div>
                                <ul id="selectPayment" class="dropdown">
                                    @foreach ($paymentTypes as $paymentType)
                                        <li rel="{{ $paymentType->name }}" data-name="{{ $paymentType->name }}"
                                            data-id="{{ $paymentType->id }}" class="payment-item" style="color: black;">
                                            {{ $paymentType->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <table id="bankAccountsTable" class="table table-striped" style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Bank List</th> <!-- ✅ Changed column name -->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <div class="form-row" style="margin-top: 0px;">
                        <button class="back-navigation backSecond">Back
                            <i class="zmdi zmdi-long-arrow-left"></i>
                        </button>

                        <button class="forwardFourth">NEXT
                            <i class="zmdi zmdi-long-arrow-right"></i>
                        </button>
                    </div>
                </section>

                <h4>Confirmation</h4>
                <section class="">
                    <div id="purchaseDetails" style="width: 100%;">

                    </div>
                    <div class="form-row" style="margin-top: 0px;">
                        <button class="back-navigation backLast">Back
                            <i class="zmdi zmdi-long-arrow-left"></i>
                        </button>

                        <button class="forwardLast">FINISH PAYMENT
                            <i class="zmdi zmdi-long-arrow-right"></i>
                        </button>
                    </div>
                </section>

                {{-- @if ($pricing->isNotEmpty()) --}}

                {{-- @else --}}

                {{-- @endif --}}

            </div>
        </div>
    </div>

    <script src="{{ asset('booking') }}/js/jquery-3.3.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <!-- JQUERY STEP -->
    <script src="{{ asset('booking') }}/js/jquery.steps.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- DATE-PICKER -->
    <script src="{{ asset('booking') }}/vendor/date-picker/js/datepicker.js"></script>
    <script src="{{ asset('booking') }}/vendor/date-picker/js/datepicker.en.js"></script>


    <script src="{{ asset('booking') }}/js/main.js"></script>
    <!-- Template created and distributed by Colorlib -->
    <script>
        $(document).ready(function() {
            var dp1 = $('#dp1').datepicker().data('datepicker');
            dp1.selectDate(new Date());

            let currentDayType = "{{ $jenisHari }}";
            let totalPrice = 0;
            let selectedPaymentName = "";
            let finalFormData = null;

            $('#dp1').datepicker({
                language: 'en',
                dateFormat: "dd - M - yyyy",
                onSelect: function(formattedDate, dateObj) {
                    if (dateObj) {
                        let dayType = (dateObj.getDay() === 0 || dateObj.getDay() === 6) ? 'Weekend' :
                            'Weekday';
                        console.log(`Selected Date: ${formattedDate}, Day Type: ${dayType}`);
                        sendDayTypeToController(dayType);
                        resetTicketSummary();
                    }
                }
            });

            function sendDayTypeToController(dayType) {
                $.ajax({
                    url: "{{ route('booking.updateDayType') }}",
                    type: "POST",
                    data: {
                        day_type: dayType,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        currentDayType = response.day_type; // ✅ Update current day type
                    },
                    error: function(xhr, status, error) {
                        console.error("Error sending day type:", error);
                    }
                });
            }

            $('#bankAccountsTable').hide();
            let pricingData = @json($pricing);

            $('.destination-item').on('click', function() {
                let imageUrl = $(this).data('image'); // ✅ Get stored image URL

                $('#selectedImage').fadeOut(200, function() { // ✅ Smooth fade effect
                    $(this).attr('src', imageUrl).fadeIn(200);
                });

                let destinationId = $(this).data('id'); // ✅ Get selected destination ID

                $("#selectDestinationId").val(destinationId); // ✅ Set hidden input value

                // Load pricing dynamically using AJAX
                $.ajax({
                    url: "{{ route('get.pricing') }}", // ✅ Laravel route
                    type: "GET",
                    data: {
                        destination_id: destinationId
                    },
                    success: function(response) {
                        renderPricing(response); // ✅ Call function to update the pricing board
                    }
                });

            });

            function validateFirstSection() {
                let destination = $(".select-control").text().trim();
                let date = $("#dp1").val().trim();
                let peopleCount = $("input[name='people_count']").val().trim();

                if (destination === "-- Please select --" || destination === "") {
                    toastr.error("Error: Please select a destination!", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    });
                    return false;
                }

                if (date === "") {
                    console.log("Error: Please select a date!");
                    toastr.error("Error: Please select a date!", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    });
                    return false;
                }

                if (peopleCount === "" || isNaN(peopleCount) || peopleCount <= 0) {
                    console.log("Error: Please enter a valid number of people!");
                    toastr.error("Error: Please enter a valid number of people!", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    });
                    return false;
                }

                console.log("Validation passed: All fields are valid!");
                return true; // ✅ Validation passed
            }

            function validateSecondSection() {
                let name = $("input[name='name']").val().trim();
                let address = $("input[name='address']").val().trim();
                let province = $("#provinceSearch").val().trim();
                let regency = $("#regencySearch").val().trim();
                let district = $("#districtSearch").val().trim();
                let phone = $("input[name='phone']").val().trim();
                let email = $("input[name='email']").val().trim();
                let origin = $("textarea[name='origin']").val().trim();

                if (name === "") {
                    console.log("Error: Name is required!");
                    toastr.error("Error: Name is required!", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    })
                    return false;
                }

                if (address === "") {
                    console.log("Error: Address is required!");
                    toastr.error("Error: Address is required!", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    })
                    return false;
                }

                if (province === "") {
                    console.log("Error: Province is required!");
                    toastr.error("Error: Province is required!", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    })
                    return false;
                }

                if (regency === "") {
                    console.log("Error: Regency is required!");
                    toastr.error("Error: Regency is required!", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    })
                    return false;
                }

                if (district === "") {
                    console.log("Error: District is required!");
                    toastr.error("Error: District is required!", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    })
                    return false;
                }

                if (phone === "" || isNaN(phone) || phone.length < 6) {
                    console.log("Error: Please enter a valid phone number!");
                    toastr.error("Error: Please enter a valid phone number!", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    })
                    return false;
                }

                if (email === "" || !email.includes("@")) {
                    console.log("Error: Please enter a valid email!");
                    toastr.error("Error: Please enter a valid email!", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    })
                    return false;
                }

                if (origin === "") {
                    console.log("Error: Origin is required!");
                    toastr.error("Error: Origin is required!", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    })
                    return false;
                }

                console.log("Validation passed: All fields are valid!");
                return true; // ✅ Validation passed
            }

            function validatePaymentOption(){
                let paymentOption = $("#selectPaymentId").val().trim();
                if (paymentOption === "") {
                    toastr.error("Error: Payment option is required!", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    })
                    return false;
                }
                return true;
            }

            // back-navigation
            $(".back-navigation").click(function(event) {
                event.preventDefault();

                console.log("Back button clicked!"); // ✅ Debugging

                $("#wizard").steps("previous");
            });

            // ✅ Attach validation function to NEXT button

            $(".forwardLast").click(function(event) {
                event.preventDefault(); // ✅ Prevent default submission
                finishPayment(); // ✅ Call function to finish payment
                console.log("Finish payment button clicked!"); // ✅ Debugging
            });


            $(".forwardThird").click(function(event) {
                event.preventDefault(); // ✅ Prevent default submission

                if (validateTicketOptionValue()) {
                    console.log("Validation passed: All fields are valid!");
                    $("#wizard").steps('next'); // ✅ Proceed to next step
                }
            });

            $(".forwardSecond").click(function(event) {
                event.preventDefault(); // ✅ Prevent default submission

                if (validateSecondSection()) {
                    console.log("Validation passed: All fields are valid!");
                    $("#wizard").steps('next'); // ✅ Proceed to next step
                }
            });


            $(".forwardFirst").click(function(event) {
                event.preventDefault(); // ✅ Prevent default submission

                if (validateFirstSection()) {
                    console.log("Validation passed: All fields are valid!");
                    $("#wizard").steps('next');
                }
            });

            $(".forwardFourth").on("click", function(event) {
                event.preventDefault(); // Prevent default button behavior (if it's inside a form)

                if (validatePaymentOption()) {
                    let formData = {}; // Object to store collected inputs

                    // Collect all input fields inside the four sections
                    $("section input, section textarea, section select").each(function() {
                        let inputName = $(this).attr("name"); // Get input name attribute

                        formData['bank_name'] = 'none';
                        // Special handling for radio buttons
                        if ($(this).is(":radio")) {
                            if ($(this).is(":checked")) {
                                formData[inputName] = $(this).val();
                                formData['bank_name'] = $(this).data('bank-name'); // Get bank name from data attribute
                                selectedPaymentName = $(this).data('bank-name');
                                console.log('bank', $(this).data('bank-name'));
                            }
                        } else {
                            // Standard inputs, textareas, and selects
                            formData[inputName] = $(this).val();
                        }
                    });

                    formData['total_price'] = totalPrice; // Add total price to form data

                    console.log("Collected Form Data:", formData);
                    renderPurchaseDetails(formData); // ✅ Call function to render purchase details
                    finalFormData = formData; // Store final form data for later use

                    if(formData['bank_name'] == 'none'){
                        toastr.error("Error: Please select a bank transfer!", "Error", {
                            timeOut: 3000,
                            progressBar: true
                        });
                        return false;
                    }

                    $("#wizard").steps('next');
                }


            });

            function finishPayment() {
                $.ajax({
                    url: "{{ route('booking.finishPayment') }}",
                    type: "POST",
                    data: {
                        formData: finalFormData,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log("Payment finished successfully:", response);

                        // ✅ Extract 'id' from response.data
                        let orderId = response.encrypted_id;

                        // ✅ Redirect to the next page with correct order ID
                        let url = "{{ route('finish.payment.view', ['id' => ':id']) }}".replace(':id',
                            orderId);
                        window.location.href = url;
                    },
                    error: function(xhr, status, error) {
                        console.error("Error finishing payment:", error);
                    }
                });
            }



            // function renderPricing(pricingData) {
            //     let pricingBoard = '';

            //     if (pricingData.length === 0) {
            //         pricingBoard = `
        //             <div class="board-wrapper" style="margin-bottom: 30px;">
        //                 <div class="board-inner">
        //                     <div class="board-item" style="font-weight: 600;">
        //                         Tickets are not available.
        //                     </div>
        //                 </div>
        //             </div>`;
            //     } else {
            //         pricingBoard += `<div class="board-wrapper" style="margin-bottom: 30px;">`;

            //         ['weekday', 'weekend'].forEach(dayType => {
            //             let dayPrices = pricingData.filter(price => price.day_type === dayType);

            //             if (dayPrices.length) {
            //                 pricingBoard += `
        //                     <div class="board-inner">
        //                         <div class="board-item" style="font-weight: 600;">${dayType.charAt(0).toUpperCase() + dayType.slice(1)} :</div>
        //                         <div class="board-line">`;

            //                 dayPrices.forEach(price => {
            //                     pricingBoard += `
        //                         <div class="board-item">
        //                             ${price.guest_name} :
        //                             <br>
        //                             <span>Rp. ${parseFloat(price.final_price).toLocaleString()}</span>
        //                         </div>`;
            //                 });

            //                 pricingBoard += `</div></div>`;
            //             }
            //         });

            //         pricingBoard += `</div>`;

            //         // ✅ Add booking fields ONLY if pricing exists
            //         pricingBoard += `
        //             <div class="form-row">
        //                 <div class="form-holder w-100">
        //                     <input type="number" class="form-control pl-85" value="0">
        //                     <span class="placeholder">Kids:</span>
        //                 </div>
        //             </div>
        //             <div class="form-row">
        //                 <div class="form-holder w-100">
        //                     <input type="number" class="form-control pl-85" value="0">
        //                     <span class="placeholder">Adults:</span>
        //                 </div>
        //             </div>
        //             <div class="form-row">
        //                 <div class="form-holder w-100">
        //                     <input type="number" class="form-control pl-85" value="0">
        //                     <span class="placeholder">Foreigners:</span>
        //                 </div>
        //             </div>
        //             <button class="forward" >Book by email
        //                 <i class="zmdi zmdi-long-arrow-right"></i>
        //             </button>`;
            //     }

            //     $('#pricingBoard').html(pricingBoard); // ✅ Update the pricing board dynamically
            // }

            let dateFromServer = "{{ $searchData['daterange'] ?? '' }}"; // ✅ Get date from backend

            if (dateFromServer) {
                let formattedDate = new Date(dateFromServer); // ✅ Convert string to Date object
                let dp1 = $('#dp1').datepicker().data('datepicker');

                dp1.selectDate(formattedDate); // ✅ Set datepicker value dynamically
            }

            $("#provinceSearch").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('get.provinces') }}",
                        type: "GET",
                        data: {
                            search: request.term
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.name,
                                    value: item.name,
                                    id: item.id
                                };
                            }));
                        }
                    });
                },
                select: function(event, ui) {
                    $('#regencySearch').prop('disabled', false).val(''); // ✅ Enable regency search
                    $('#districtSearch').prop('disabled', true).val(''); // ✅ Reset district input

                    loadRegencies(ui.item.id);
                },
                minLength: 1
            });

            function loadRegencies(provinceId) {
                $("#regencySearch").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "{{ route('get.regencies') }}",
                            type: "GET",
                            data: {
                                province_id: provinceId,
                                search: request.term
                            },
                            success: function(data) {
                                response($.map(data, function(item) {
                                    return {
                                        label: item.name,
                                        value: item.name,
                                        id: item.id
                                    };
                                }));
                            }
                        });
                    },
                    select: function(event, ui) {
                        $('#districtSearch').prop('disabled', false).val(
                            ''); // ✅ Enable district search
                        loadDistricts(ui.item.id);
                    },
                    minLength: 1
                });
            }

            function loadDistricts(regencyId) {
                $("#districtSearch").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "{{ route('get.districts') }}",
                            type: "GET",
                            data: {
                                regency_id: regencyId,
                                search: request.term
                            },
                            success: function(data) {
                                response($.map(data, function(item) {
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

            // function loadPaymentMethods() {
            //     $.ajax({
            //         url: "{{ route('admin.payment-option.list') }}",
            //         type: "GET",
            //         success: function (response) {
            //             let paymentOptions = '';

            //             response.forEach(payment => {
            //                 paymentOptions += `<li rel="${payment.name}" data-name="${payment.name}" class="payment-item">${payment.name}</li>`;
            //             });

            //             $("#selectPayment").html(paymentOptions);
            //         },
            //         error: function (xhr) {
            //             console.error("Failed to load payment options:", xhr.responseText);
            //         }
            //     });
            // }

            // loadPaymentMethods(); // ✅ Ensure payment methods are

            $('.payment-item').on('click', function() {
                let paymentName = $(this).data('name'); // ✅ Get selected payment name
                console.log('paymentName:', paymentName);
                selectedPaymentName = paymentName; // ✅ Store selected payment name

                $('#selectPaymentId').val($(this).data('id')); // ✅ Set hidden input value

                if (paymentName == 'Bank Transfer') {
                    $('#bankAccountsTable').show(); // ✅ Show bank accounts table
                    loadBankAccounts(); // ✅ Load bank accounts dynamically
                } else {
                    $('#bankAccountsTable').hide(); // ✅ Hide bank accounts table
                }
            });

            function loadBankAccounts() {
                $.ajax({
                    url: "{{ route('bank.accounts.list') }}",
                    type: "GET",
                    success: function(response) {
                        let tableBody = '';

                        response.forEach((bank, index) => {
                            tableBody += `
                                <tr>
                                    <td>
                                        <input type="radio" name="bankSelection" data-bank-name="${bank.bank_name}" value="${bank.id}" id="bank_${index}">
                                    </td>
                                    <td>
                                        <strong>${bank.bank_name}</strong><br>
                                        ${bank.account_number}<br>
                                        atas nama ${bank.account_name}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><hr></td> <!-- ✅ Adds horizontal line between rows -->
                                </tr>

                            `;
                        });
                        $('#bankAccountsTable').show();
                        $("#bankAccountsTable tbody").html(
                            tableBody); // ✅ Inject formatted data into table
                    },
                    error: function(xhr) {
                        console.error("Failed to load bank accounts:", xhr.responseText);
                    }
                });
            }

            function resetTicketSummary() {
                console.log("Resetting ticket summary...");

                $("input[type='number']").each(function() {
                    let guestTypeName = $(this).attr("name"); // kids, adults, foreigners
                    $(this).val(0);

                    // Find matching pricing based on the current dayType
                    let matchingPricing = pricingData.find(price =>
                        price.guest_name.toLowerCase() === guestTypeName &&
                        price.day_type.toLowerCase() === currentDayType.toLowerCase()
                    );

                    if (matchingPricing) {

                        // Update the corresponding ticket summary row dynamically
                        $(`#selectedTicket_${matchingPricing.guest_name_id}`).text(`x 0`);
                        $(`#ticketPrice_${matchingPricing.guest_name_id}`).text(`Rp. 0`);
                    } else {
                        console.warn(
                            `No matching pricing found for Guest Type: ${guestTypeName}, Day Type: ${currentDayType}`
                        );
                    }
                });

                $("#ticketSummaryTable tr:last-child td:last-child").text(`Rp. 0`);
            }

            function updateTicketSummary() {

                console.log("Updating ticket summary...");

                totalPrice = 0; // Reset total price

                $("input[type='number']").each(function() {
                    let guestTypeName = $(this).attr("name"); // kids, adults, foreigners
                    let quantity = parseInt($(this).val()) || 0;

                    console.log(`Guest Type: ${guestTypeName}, Quantity: ${quantity}`);

                    // Find matching pricing based on the current dayType
                    let matchingPricing = pricingData.find(price =>
                        price.guest_name.toLowerCase() === guestTypeName &&
                        price.day_type.toLowerCase() === currentDayType.toLowerCase()
                    );
                    console.log('currentDayType:', currentDayType);
                    console.log(`Matching Pricing: ${JSON.stringify(matchingPricing)}`);

                    if (matchingPricing) {
                        let finalPrice = matchingPricing.final_price * quantity;
                        totalPrice += finalPrice;

                        console.log(
                            `Matched Pricing -> Guest Name: ${matchingPricing.guest_name}, Day Type: ${matchingPricing.day_type}, Final Price: ${finalPrice}`
                        );

                        // Update the corresponding ticket summary row dynamically
                        $(`#selectedTicket_${matchingPricing.guest_name_id}`).text(`x ${quantity}`);
                        $(`#ticketPrice_${matchingPricing.guest_name_id}`).text(
                            `Rp. ${finalPrice.toLocaleString('id-ID')}`);
                    } else {
                        console.warn(
                            `No matching pricing found for Guest Type: ${guestTypeName}, Day Type: ${currentDayType}`
                        );
                    }
                });

                // Update the total price in the summary table
                console.log(`Total Price Updated: Rp. ${totalPrice.toLocaleString('id-ID')}`);
                $("#ticketSummaryTable tr:last-child td:last-child").text(
                    `Rp. ${totalPrice.toLocaleString('id-ID')}`);
            }
            // Attach event listeners to number input fields
            $("input[type='number']").on("input", updateTicketSummary);

            function validateTicketOptionValue(){
                if(totalPrice == 0){
                    toastr.error("Error: Please select at least one ticket option.", "Error", {
                        timeOut: 3000,
                        progressBar: true
                    });
                    return false;
                }
                return true;
            }

            function renderPurchaseDetails(data) {
                let kategoriTiket = data.people_count > 1 ? "Group" : "Single"; // ✅ Group if >1, else Single

                let visitorKeys = ["anak-anak", "dewasa", "mancanegara"];
                let totalPengunjung = visitorKeys.reduce((sum, key) => sum + Number(data[key] || 0), 0);

                let totalHarga = `Rp. ${(totalPrice).toLocaleString()},-`; // ✅ Calculate price dynamically

                if (selectedPaymentName == 'Bank Transfer') {
                    let bankName = $("input[name='bankSelection']:checked").data('bank-name');
                    selectedPaymentName = 'Transfer - ' + bankName;
                }

                let tableHTML = `
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                        <tr>
                            <th colspan="2" style="text-align: center; padding: 10px; background-color: #f8f8f8; border-bottom: 2px solid #ccc; color: black">
                                Rincian Pembelian Tiket
                            </th>
                        </tr>
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #ddd;">Nama</td>
                            <td style="padding: 12px; border-bottom: 1px solid #ddd;">${data.name}</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #ddd;">Email</td>
                            <td style="padding: 12px; border-bottom: 1px solid #ddd;">${data.email}</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #ddd;">Alamat</td>
                            <td style="padding: 12px; border-bottom: 1px solid #ddd;">${data.address}</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #ddd;">Kategori Tiket</td>
                            <td style="padding: 12px; border-bottom: 1px solid #ddd;">${data.people_count > 1 ? "Rombongan" : "Single"}</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #ddd;">Total Pengunjung</td>
                            <td style="padding: 12px; border-bottom: 1px solid #ddd;">${totalPengunjung} Pengunjung</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #ddd;">Metode Bayar</td>
                            <td style="padding: 12px; border-bottom: 1px solid #ddd;">${selectedPaymentName}</td>
                        </tr>
                        <tr style="background-color: #edc948; border-top: 2px solid #ccc;">
                            <td style="padding: 12px; border-bottom: 1px solid #ddd; color: black;">Total Harga</td>
                            <td style="padding: 12px; border-bottom: 1px solid #ddd; color: black;">${totalHarga}</td>
                        </tr>
                    </table>
                `;

                $("#purchaseDetails").html(tableHTML); // ✅ Inject the updated table into the page
            }


        });
    </script>
</body>

</html>
