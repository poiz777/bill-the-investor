@extends('layouts.stocks-layout')

@section('pageTitle')
    {{ $pageTitle }}
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
                    @if(isset($navRoutes) and !empty($navRoutes))
                        @include("partials.nav-menu-horizontal", ['navRoutes' => $navRoutes])
                    @endif
                @endif

                <div class="col-md-12 no-lr-pad pz-content-right">
                    <h1 class="pz-stock-title pz-centered">{{ $pageTitle }}</h1>
                    @if(isset($dataSet) and !empty($dataSet))
                        @include('partials.market-overview-loop-data', ['dataSet' => $dataSet])
                    @else
                        <div class="well">
                            <h2>NO DATA</h2>
                            <p>Currently, we couldn't fetch Real-Time Stock Data due to the Limitation of our API. Please check back soonest...</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerScripts')
@endSection
