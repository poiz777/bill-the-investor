<ul class="list-group list-unstyled pz-u-list">
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
            <li class="list-group-item pz-list-item {{ $prefCommonClass }}">
                <p class="pz-company-name pz-stock-item">{{$companyInfo->name}}</p>
                <p class="pz-stock-type pz-stock-item">@if(isset($companyInfo->securities->security_type)){{ $companyInfo->securities->security_type }}@endif</p>
                <p class="pz-stock-price pz-stock-item">@if(isset($companyInfo->securities->currency)){{ $companyInfo->securities->currency }}@endif {{ number_format($data->open, '2', ',', "'") }}</p>
                <p class="pz-stock-broker pz-stock-item">@if(isset($companyInfo->securities->exch_symbol)){{ $companyInfo->securities->exch_symbol}}@endif</p>
            </li>
        @endif
    @endforeach
</ul>
