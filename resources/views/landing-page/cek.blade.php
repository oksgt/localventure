<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="favicon.png">

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Source+Serif+Pro:wght@400;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('landing-page') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('landing-page') }}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('landing-page') }}/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{ asset('landing-page') }}/css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="{{ asset('landing-page') }}/fonts/icomoon/style.css">
    <link rel="stylesheet" href="{{ asset('landing-page') }}/fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="{{ asset('landing-page') }}/css/daterangepicker.css">
    <link rel="stylesheet" href="{{ asset('landing-page') }}/css/aos.css">
    <link rel="stylesheet" href="{{ asset('landing-page') }}/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <title>Local Venture</title>
</head>

<body>
    <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close">
                <span class="icofont-close js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>

    <nav class="site-nav">
        <div class="container">
            <div class="site-navigation">
                {{-- <a href="index.html" class="logo m-0">LocalVenture <span class="text-primary">.</span></a> --}}
                <div>
                    <img src="{{ asset('storage/assets/image/logo-2.png') }}" alt="localVenture" style="width: 170px">
                </div>

                {{-- <ul class="js-clone-nav d-none d-lg-inline-block text-left site-menu float-right">
					<li class="active"><a href="index.html">Home</a></li>
					<li class="has-children">
						<a href="#">Dropdown</a>
						<ul class="dropdown">
							<li><a href="elements.html">Elements</a></li>
							<li><a href="#">Menu One</a></li>
							<li class="has-children">
								<a href="#">Menu Two</a>
								<ul class="dropdown">
									<li><a href="#">Sub Menu One</a></li>
									<li><a href="#">Sub Menu Two</a></li>
									<li><a href="#">Sub Menu Three</a></li>
								</ul>
							</li>
							<li><a href="#">Menu Three</a></li>
						</ul>
					</li>
					<li><a href="services.html">Services</a></li>
					<li><a href="about.html">About</a></li>
					<li><a href="contact.html">Contact Us</a></li>
				</ul>

				<a href="#" class="burger ml-auto float-right site-menu-toggle js-menu-toggle d-inline-block d-lg-none light" data-toggle="collapse" data-target="#main-navbar">
					<span></span>
				</a> --}}

            </div>
        </div>
    </nav>

    <div class="hero hero-inner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mx-auto text-center">
                    <div class="intro-wrap">
                        <h1 class="mb-0">Detail Tiket</h1>
                        <p class="text-white">{{ $billing }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="untree_co-section">
        <div class="container ">
            <div class="row p-2">
                <div class="col-lg-7 ">
                    <div class="owl-single dots-absolute owl-carousel">
                        <img src="{{ $selectedImage }}" alt="Image" class="img-thumbnail p-0 rounded-20"
                            style="width: {{ $img_width }}">
                    </div>
                </div>
                <div class="col-lg-5 p-0 pl-lg-5 ml-auto mt-4">
                    <h2 class="section-title mb-4">Tiket : {{ $destination->name }}</h2>

                    <ul class="list-unstyled two-col clearfix">
                        <li>Nama: {{ $transaction->visitor_name }}</li>
                        <li>Tanggal Visit: {{ Carbon\Carbon::parse($transaction->visit_date)->format('d F Y') }}</li>
                        <li>Jumlah: {{ $transaction->total_visitor }} orang</li>
                        <li>Status:
                            @if ($transaction->payment_status == 'pending')
                                <span
                                    class="badge badge-warning p-2">{{ ucwords($transaction->payment_status) }}</span>
                            @elseif ($transaction->payment_status == 'paid')
                                <span
                                    class="badge badge-success p-2">{{ ucwords($transaction->payment_status) }}</span>
                            @else
                                <span class="badge badge-danger p-2">{{ ucwords($transaction->payment_status) }}</span>
                            @endif

                        </li>
                    </ul>


                    @if ($confirmation->isNotEmpty())
                        @foreach ($confirmation as $item)
                            @if ($item->status == 2)
                                <!-- jika rejected -->
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>Oups!</strong> Nampaknya kamu belum selesaikan pembayarannya ya? <br>
                                    Konfirmasi yuk
                                    kalau sudah :)
                                </div>
                                <p><a href="#" class="btn btn-primary" data-toggle="modal"
                                        data-target="#paymentModal">Konfirmasi</a></p>
                            @elseif ($item->status == 0)
                                <!-- jika pending -->
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    Mohon ditunggu yaa, konfirmasi kamu sedang diproses :)
                                </div>
                            @elseif ($item->status == 1)
                                <!-- jika approve -->
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Selamat!</strong> Konfirmasi kamu telah disetujui
                                </div>
                                <div class="btn-group w-100" role="group" aria-label="Basic example">
                                    <a type="button" class="btn btn-outline-success"
                                        href="{{ url('/download-invoice/' . $encrypted_id) }}">Download Invoice</a>
                                    <a type="button" class="btn btn-success"
                                        href="{{ url('/download/ticket/baru/' . $encrypted_id) }}">Download Ticket</a>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Oups!</strong> Nampaknya kamu belum selesaikan pembayarannya ya? <br> Konfirmasi yuk
                            kalau sudah :)
                        </div>

                        <p><a href="#" class="btn btn-primary" data-toggle="modal"
                                data-target="#paymentModal">Konfirmasi</a></p>
                    @endif


                </div>
            </div>

            @if ($confirmation->isNotEmpty())
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped mt-4">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nominal</th>
                                    <th>Nama Bank</th>
                                    <th>Nama Akun</th>
                                    <th>Nomor Rekening</th>
                                    <th>Bukti Transfer</th>
                                    <th>Status</th>
                                    <th>Tanggal Konfirmasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($confirmation as $item)
                                    @php
                                        $badgeStatus = '<span class="badge badge-warning p-2">Oncheck</span>';
                                        if ($item->status == 1) {
                                            $badgeStatus = '<span class="badge badge-success p-2">Approved</span>';
                                        } elseif ($item->status == 2) {
                                            $badgeStatus = '<span class="badge badge-danger p-2">Rejected</span>';
                                        }
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ 'Rp ' . number_format($item->transfer_amount, 2, ',', '.') }}</td>
                                        <td>{{ $item->bank_name }}</td>
                                        <td>{{ $item->account_name }}</td>
                                        <td>{{ $item->account_number }}</td>
                                        <td><a href="#" class="btn btn-link p-0" data-toggle="modal" data-target="#imageModal"
                                                data-image="{{ asset('storage/' . $item->image) }}">
                                                Lihat Bukti
                                            </a></td>
                                        <td>{!! $badgeStatus !!}</td>
                                        <td>{{ Carbon\Carbon::parse($item->created_at)->format('d F Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <div class="site-footer">
        <div class="inner first">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <div class="widget">
                            <h3 class="heading">About LocalVenture</h3>
                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia,
                                there live the blind texts.</p>
                        </div>
                        <div class="widget">
                            <ul class="list-unstyled social">
                                <li><a href="#"><span class="icon-twitter"></span></a></li>
                                <li><a href="#"><span class="icon-instagram"></span></a></li>
                                <li><a href="#"><span class="icon-facebook"></span></a></li>
                                <li><a href="#"><span class="icon-linkedin"></span></a></li>
                                <li><a href="#"><span class="icon-dribbble"></span></a></li>
                                <li><a href="#"><span class="icon-pinterest"></span></a></li>
                                <li><a href="#"><span class="icon-apple"></span></a></li>
                                <li><a href="#"><span class="icon-google"></span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2 pl-lg-5">
                        <div class="widget">
                            <h3 class="heading">Pages</h3>
                            <ul class="links list-unstyled">
                                <li><a href="#">Pesan Tiket</a></li>
                                <li><a href="#">Konfirmasi</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>



        <div class="inner dark">
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-8 mb-3 mb-md-0 mx-auto">
                        <p>Copyright &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script>. All Rights Reserved.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="overlayer"></div>
    <div class="loader">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <!-- ✅ Payment Confirmation Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="paymentForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <input type="hidden" name="ticket_order_id" id="ticket_order_id"
                                    value="{{ $transaction->id }}"> <!-- ✅ Hidden -->

                                <div class="form-group">
                                    <label>Invoice Number</label>
                                    <input type="text" class="form-control" name="billing_number"
                                        id="billing_number" readonly required
                                        value="{{ $transaction->billing_number }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="form-group">
                                    <label>Jumlah Transfer</label>
                                    <input type="text" class="form-control" name="transfer_amount"
                                        id="transfer_amount" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="form-group">
                                    <label>Nama Bank</label>
                                    <input type="text" class="form-control" name="bank_name">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="form-group">
                                    <label>Nama Rekening</label>
                                    <input type="text" class="form-control" name="account_name">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="form-group">
                                    <label>Nomor Rekening</label>
                                    <input type="text" class="form-control" name="account_number">
                                    <div class="invalid-feedback"></div>
                                </div>

                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Bukti Transfer</label>
                                    <input type="file" class="form-control" name="image" id="imageUpload">
                                    <div class="invalid-feedback"></div>
                                    <img id="imagePreview" class="mt-2" style="max-width: 100%;">
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-kirim" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ✅ Bootstrap Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bukti Transfer</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="Bukti Transfer">
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('landing-page') }}/js/jquery-3.4.1.min.js"></script>
    <script src="{{ asset('landing-page') }}/js/popper.min.js"></script>
    <script src="{{ asset('landing-page') }}/js/bootstrap.min.js"></script>
    <script src="{{ asset('landing-page') }}/js/owl.carousel.min.js"></script>
    <script src="{{ asset('landing-page') }}/js/jquery.animateNumber.min.js"></script>
    <script src="{{ asset('landing-page') }}/js/jquery.waypoints.min.js"></script>
    <script src="{{ asset('landing-page') }}/js/jquery.fancybox.min.js"></script>
    <script src="{{ asset('landing-page') }}/js/aos.js"></script>
    <script src="{{ asset('landing-page') }}/js/moment.min.js"></script>
    <script src="{{ asset('landing-page') }}/js/daterangepicker.js"></script>
    <script src="{{ asset('landing-page') }}/js/typed.js"></script>
    <script src="{{ asset('landing-page') }}/js/custom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/inputmask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('transfer_amount').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-numeric characters
            if (value) {
                e.target.value = new Intl.NumberFormat('id-ID').format(
                value); // Format as Indonesian currency (1.000, 10.000, etc.)
            }
        });

        $(document).ready(function() {

            $('#imageModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var imageUrl = button.data('image'); // Get image URL from data attribute

                $('#modalImage').attr('src', imageUrl); // Set modal image src
            });

            $('#btn-kirim').on('click', function(e) {
                e.preventDefault();

                var formData = new FormData($('#paymentForm')[0]);

                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    type: "POST",
                    url: "{{ route('payment.store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(() => {
                            location.reload();
                        });
                        $('#paymentModal').modal('hide');
                    },
                    error: function(response) {
                        var errors = response.responseJSON.errors;
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').text('');

                        $.each(errors, function(field, message) {
                            var input = $('#paymentForm [name="' + field + '"]');
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').text(message);
                        });
                    }
                });
            });



            $('#imageUpload').on('change', function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
</body>

</html>
