@php $counter = 0; @endphp
<div class="pz-wrapper-block no-lr-pad col-md-12">
    @if(isset($dataSet) and !empty($dataSet))
        @foreach($dataSet as $iKey=>$data)
            @php
                $companyInfo        = $data->companyInfo;
                $prefCommonClass    = '';
                $prefCommonName     = '';
                if(isset($companyInfo->securities->security_type)){
                    $prefCommonClass    = ($companyInfo->securities->security_type == 'Ordinary Shares') ? 'pz-common'      : 'pz-preferred';
                    $prefCommonName     = ($companyInfo->securities->security_type == 'Ordinary Shares') ? 'Common Stock'   : 'Preferred Stock';
                }
            @endphp
            @if(isset($companyInfo->name) and isset($companyInfo->securities))
                @if( ($counter % 3) == 0 and ($counter!=0))
</div><div class="pz-wrapper-block no-lr-pad col-md-12">
    @endif
    <div class="col-md-4 pz-wrapper">
        <div class="pz-list-item {{ $prefCommonClass }}">
            <header class="pz-header {{ $prefCommonClass }} col-md-12">
                <p class="pz-company-name pz-stock-item">{{$companyInfo->name}}</p>
                <p class="pz-stock-type pz-stock-item">@if(isset($companyInfo->securities->security_type)){{ $companyInfo->securities->security_type }}@endif</p>
            </header>
            <aside class="pz-aside col-md-12">
                <div class="pz-stock-exchange col-md-6 no-lr-pad">@if(isset($companyInfo->securities->exch_symbol)){{ $companyInfo->securities->exch_symbol}}@endif</div>
                <div class="pz-stock-price col-md-6 no-lr-pad">@if(isset($companyInfo->securities->currency))<span class="{{ $prefCommonClass }}">{{ $companyInfo->securities->currency }}@endif {{ number_format($data->open, '2', ',', "'") }}</span></div>
            </aside>
        </div>
    </div>
    @php $counter ++; @endphp
    @endif
    @endforeach
    @else
        <div class="well">
            <h2>NO DATA</h2>
            <p>Currently, we couldn't fetch Real-Time Stock Data due to the Limitation of our API. Please check back soonest...</p>
        </div>
    @endif
</div>
