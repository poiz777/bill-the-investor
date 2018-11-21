<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

@yield('extraHeadMeta')

<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('pageTitle')
    </title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
@yield('headScripts')

<!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">

    @yield('extraCSS')
</head>

<body id="pz-body-main" class="pz-body-main">
<div id="static_bar_top" class="navbar navbar-inverse navbar-fixed-top"
     role="navigation">
    <div class="container">
        <div class="navbar-header">
            <div class="pz-header-central">
                <div class="pz-header-central container">
                    <div class="pz-wrapper col-md-12 no-padding no-lr-pad">

                        <div class="pz-logo-block col-md-6 no-l-pad">
                            <a href="{{-- url('rte_home') --}}">
                                <img src="{{ asset('images/logos/Poiz_insignia.png') }}" alt="Poiz Logo" class="pz-logo" id="pz-logo" />
                            </a>
                        </div>
                        <div class="pz-avatar-block col-md-6">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section id="pz-main-wrapper" class="pz-main-wrapper container">
    <section class="pz-col-12">
        <section>
            <!-- ERRORS -->
            @yield('errors')
        </section>
        <section>
            @yield('content')
        </section>
    </section>
</section>
<!-- FOOTER SCRIPTS -->
@yield('footerScripts')

</body>
</html>
