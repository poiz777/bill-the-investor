
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
                    @include("partials.nav-menu-horizontal", ['navRoutes' => $navRoutes])
                @endif

                <div class="col-md-12 no-lr-pad pz-content-right">
                    <h1 class="pz-stock-title pz-centered">{{ $pageTitle }}</h1>
                    <section class="pz-stock-section">
                        <div class="col-md-3">&nbsp;</div>
                        <div class="col-md-6">
                            <div class="pz-error-block">
                                @if(isset($pzErrors) && $pzErrors)
                                    {!! $pzErrors !!}
                                @endif
                            </div>
                            @if($stockForm)
                                {!! $stockForm['formOpen'] !!}
                                {!! $stockForm['symbol'] !!}
                                {!! $stockForm['quote_date'] !!}
                                {!! $stockForm['submit'] !!}
                                @csrf
                                {!! $stockForm['formClose'] !!}
                            @endif
                        </div>
                        <div class="col-md-3">&nbsp;</div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerScripts')
@endSection
