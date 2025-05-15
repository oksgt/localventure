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
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Source+Serif+Pro:wght@400;700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="{{ asset('landing-page') }}/css/bootstrap.min.css">
	<link rel="stylesheet" href="{{ asset('landing-page') }}/css/owl.carousel.min.css">
	<link rel="stylesheet" href="{{ asset('landing-page') }}/css/owl.theme.default.min.css">
	<link rel="stylesheet" href="{{ asset('landing-page') }}/css/jquery.fancybox.min.css">
	<link rel="stylesheet" href="{{ asset('landing-page') }}/fonts/icomoon/style.css">
	<link rel="stylesheet" href="{{ asset('landing-page') }}/fonts/flaticon/font/flaticon.css">
	<link rel="stylesheet" href="{{ asset('landing-page') }}/css/daterangepicker.css">
	<link rel="stylesheet" href="{{ asset('landing-page') }}/css/aos.css">
	<link rel="stylesheet" href="{{ asset('landing-page') }}/css/style.css">

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

	<div class="hero">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-7">
					<div class="intro-wrap">
						<h1 class="mb-5"><span class="d-block">Nikmati waktu Anda</span> di <span class="typed-words"></span></h1>

						<div class="row">
							<div class="col-12">
								<form class="form" id="booking-form">
                                    @csrf
                                    <div class="row mb-2">
                                        <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-4">
                                            <label class="form-text text-muted" for="destination-select">Destinasi Wisata</label>
                                            <select name="destination_id" id="destination-select" class="form-control custom-select">
                                                <option value="">-- Pilih Destinasi --</option>
                                                @foreach ($destinations as $destination)
                                                    <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-5">
                                            <label class="form-text text-muted" for="daterange">Tanggal Kunjungan</label>
                                            <input type="text" class="form-control" name="daterange">
                                        </div>
                                        <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-3">
                                            <label class="form-text text-muted" for="people_count">Jumlah Orang</label>
                                            <input type="text" class="form-control" name="people_count" placeholder="# orang">
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-4">
                                            <input type="submit" class="btn btn-primary btn-block mt-3" value="Cari Tiket">
                                        </div>
                                        <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-4">
                                            <input type="button" class="btn btn-outline-primary btn-block mt-3" id="btn-confirm-booking" value="Konfirmasi">
                                        </div>
                                    </div>

                                    <div id="error-message" class="row align-items-center d-none">
                                        <div class="col-sm-12 col-md-12 mb-2 mt-3">
                                            <small class="text-danger h6">* Pilih destinasi, tanggal, dan jumlah orang</small>
                                        </div>
                                    </div>

                                </form>
							</div>
                            <div class="col-12">

                            </div>
						</div>
					</div>
				</div>
				<div class="col-lg-5">
                    <div class="slides">
                        @foreach ($destinations as $destination)
                            @foreach ($destination->images as $image)
                                <img src="{{ asset('storage/destination/' . basename($image->image_url)) }}" alt="Image" class="img-fluid {{ $loop->first ? 'active' : '' }}">
                            @endforeach
                        @endforeach
                    </div>
				</div>
			</div>
		</div>
	</div>


	<div class="untree_co-section">
		<div class="container">
			<div class="row justify-content-center text-center mb-5">
				<div class="col-lg-6">
                    <h2 class="section-title text-center mb-3">Destinasi Wisata Terbaik Untuk Anda</h2>
                    <p>Kami menghadirkan berbagai pilihan destinasi wisata menarik untuk Anda jelajahi. Dari keindahan alam yang memukau hingga tempat bersejarah yang penuh cerita, ada banyak pengalaman seru menanti! Temukan tempat wisata favorit Anda dan mulailah petualangan yang tak terlupakan.</p>
                </div>
			</div>
			<div class="row justify-content-center ">
                @foreach ($destinations as $destination)
                    @foreach ($destination->images as $image)
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-4 ">
                        <div class="media-1">
                            <a href="#" class="d-block mb-3"><img src="{{ asset('storage/destination/' . basename($image->image_url)) }}" alt="Image" class="img-fluid"></a>
                            <span class="d-flex align-items-center loc mb-2">
                                <span class="icon-room mr-3"></span>
                                <span>{{ $destination->address }}</span>
                            </span>
                            <div class="d-flex align-items-center">
                                <div>
                                    <h3><a href="#">{{ $destination->name }}</a></h3>
                                    <div class="price ml-auto">
                                        {{-- <span>$520.00</span> --}}
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div> @endforeach
                @endforeach

			</div>
		</div>
	</div>


	<div class="py-5 cta-section">
		<div class="container">
			<div class="row text-center">
				<div class="col-md-12">
                    <h2 class="mb-2 text-white">Temukan Wisata Lokal Terbaik. Pesan Tiketmu Sekarang!</h2>
                    <p class="mb-4 lead text-white text-white-opacity">Nikmati liburan tanpa ribet! Pesan tiket wisata dengan mudah dan mulailah petualangan seru ke berbagai destinasi menarik.</p>
                    <p class="mb-0">
                        <a href="#" onclick="scrollToTop()" class="btn btn-outline-white text-white btn-md font-weight-bold">
                            Pesan Sekarang
                        </a>
                    </p>
                </div>
			</div>
		</div>
	</div>

	<div class="site-footer">
		<div class="inner first">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-lg-4">
						<div class="widget">
							<h3 class="heading">About LocalVenture</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
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
						<p>Copyright &copy;<script>document.write(new Date().getFullYear());</script>. All Rights Reserved.
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
	<script>
		$(function() {
			var slides = $('.slides'),
			images = slides.find('img');

			images.each(function(i) {
				$(this).attr('data-id', i + 1);
			})

            var destinationNames = @json($destinationNames);

			var typed = new Typed('.typed-words', {
				strings: destinationNames.map(name => ` ${name}.`),
				typeSpeed: 80,
				backSpeed: 80,
				backDelay: 4000,
				startDelay: 1000,
				loop: true,
				showCursor: true,
				preStringTyped: (arrayPos, self) => {
					arrayPos++;
					console.log(arrayPos);
					$('.slides img').removeClass('active');
					$('.slides img[data-id="'+arrayPos+'"]').addClass('active');
				}

			});
		})
	</script>

	<script src="{{ asset('landing-page') }}/js/custom.js"></script>

    <script>
        function scrollToTop() {
            document.body.scrollIntoView({ behavior: "smooth", block: "start" }); // ✅ Ensures smooth scroll works across browsers
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#booking-form').on('submit', function(e) {
                e.preventDefault(); // ✅ Prevent default form submission

                $.ajax({
                    url: "{{ route('search.tickets') }}",
                    type: "POST",
                    data: $('#booking-form').serialize(),
                    success: function(response) {
                        window.location.href = response.redirect; // ✅ Uses Laravel's response
                    },
                    error: function(xhr) {
                        $('#error-message').removeClass('d-none');
                        $('#error-message').addClass('d-block');
                    }
                });
            });
        });
    </script>
    <script>
        document.querySelector('input[name="people_count"]').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, ''); // ✅ Removes non-numeric characters
            if (this.value < 1) this.value = 1; // ✅ Forces minimum value of 1
        });

        document.getElementById('btn-confirm-booking').addEventListener('click', function() {
            window.location.href = "{{ route('konfirmasi') }}";
        });
    </script>
</body>

</html>
