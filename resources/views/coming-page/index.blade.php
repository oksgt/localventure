<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--
    Document Title
    =============================================
    -->
    <title>{{ env('APP_NAME') }}</title>
    <!--
    Favicons
    =============================================
    -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('coming-page') }}/assets/images/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('coming-page') }}/assets/images/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('coming-page') }}/assets/images/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('coming-page') }}/assets/images/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('coming-page') }}/assets/images/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('coming-page') }}/assets/images/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('coming-page') }}/assets/images/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('coming-page') }}/assets/images/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('coming-page') }}/assets/images/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('coming-page') }}/assets/images/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('coming-page') }}/assets/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('coming-page') }}/assets/images/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('coming-page') }}/assets/images/favicons/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('coming-page') }}/assets/images/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!--
    Stylesheets
    =============================================

    -->
    <!-- Default stylesheets-->
    <link href="{{ asset('coming-page') }}/assets/lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Template specific stylesheets-->
    <link href="{{ asset('coming-page') }}/assets/lib/owl.carousel/dist/{{ asset('coming-page') }}/assets/owl.carousel.css" rel="stylesheet">
    <!-- Main stylesheet and color file-->
    <link href="{{ asset('coming-page') }}/assets/css/style.css" rel="stylesheet">
    <link id="color-scheme" href="{{ asset('coming-page') }}/assets/css/colors/default.css" rel="stylesheet">
</head>

<body data-spy="scroll" data-target=".onpage-navigation" data-offset="60">
    <main><img id="image-background" class="img-responsive" src="{{ asset('coming-page') }}/assets/images/hammock.jpg">
        <section class="main">
            <div class="content">
                <div class="container">
                    <div class="content2">
                        <h1>{{ env('APP_NAME') }}</h1>
                    </div>
                    <div class="counter" id="countdown">
                        <h2>We're really sorry..</h2>
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <p>We don't have anything to show you right now</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!--
    JavaScripts
    =============================================
    -->
    <script src="{{ asset('coming-page') }}/assets/lib/jquery/dist/jquery.js"></script>
    <script src="{{ asset('coming-page') }}/assets/lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('coming-page') }}/assets/lib/owl.carousel/dist/owl.carousel.js"></script>
    <script src="{{ asset('coming-page') }}/assets/lib/ajaxchimp/jquery.ajaxchimp.js"></script>
    <script src="{{ asset('coming-page') }}/assets/js/main.js"></script>
</body>

</html>
