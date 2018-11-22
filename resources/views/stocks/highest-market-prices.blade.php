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
                <div class="col-md-3 pz-side-bar-left">
                    @if(isset($navRoutes) and !empty($navRoutes))
                        @include('partials.nav-menu-vertical', ['navRoutes' => $navRoutes])
                    @endif
                </div>

                <div class="col-md-9 pz-content-right">
                    <h1 class="pz-stock-title"> {{ $pageTitle }}</h1>
                    @if(isset($dataSet) and !empty($dataSet))
                        @include('partials.highest-market-prices-loop-data', ['dataSet' => $dataSet])
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
