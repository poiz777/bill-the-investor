
@extends('layouts.stocks-layout')

@section('pageTitle')
    Company Stock Overview
@endSection

@section('extraCSS')
    <link href="{{ asset('css/stocks.css') }}" rel="stylesheet">
@endSection
@section('content')
    <div class="container">
        <div class="pz-stocks-container">
            <div class="pz-divider"></div>
            <div class="pz-main col-md-12  no-lr-pad">
                @include('partials.messages')

                @if(isset($navRoutes) and !empty($navRoutes))
                    @include("partials.nav-menu-horizontal", ['navRoutes' => $navRoutes])
                @endif

                <div class="col-md-12 no-lr-pad pz-content-right">
                    <h1 class="pz-stock-title pz-centered">Company Stock Overview</h1>
                    <section class="pz-stock-section">
                        @include("partials.stock-overview-loop-data", ['dataSet' => $dataSet])
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerScripts')
@endSection
