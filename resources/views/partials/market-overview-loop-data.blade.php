<section class="table-responsive">
    <table class="table table-bordered table-striped table-condensed pz-stock-table">
        <thead>
        <tr class="table-hover">
            <th>Company Name</th>
            <th>Stock Type</th>
            <th>Price Entered Date</th>
            <th>Price Entered Time</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
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
                <tr class="table-hover">
                    <td>{{$companyInfo->name}}</td>
                    <td>@if(isset($companyInfo->securities->security_type)){{ $companyInfo->securities->security_type }}@endif</td>
                    <td>
                        {{ $quoteDate }}
                    </td>
                    <td>
                        {{-- HOWEVER, SINCE WE HAVE NO ACCESS TO REAL-TIME DATA WITH ACCURATE TIMINGS, --}}
                        {{-- WE'D SIMPLY CREATE A PSEUDO-TIME: LESS THAN THE CURRENT TIME-INDEX --}}
                        @php
                            // GET CURRENT TIME IN HOURS & GENERATE A RANDOM NUMBER BETWEEN 0 AND (CURRENT TIME -1)
                            $currentHour    = ltrim( date('H'), '0' );
                            $pseudoHour     = ($tmp = rand(0, ($currentHour-1))) < 10 ? '0'.$tmp : $tmp;
                            $pseudoMinutes  = ($tmp = rand(0, 59)) < 10 ? '0' . $tmp : $tmp;
                        @endphp
                        {{ $pseudoHour }}:{{ $pseudoMinutes }}
                    </td>
                    <td class="pz-price">
                                                <span class="{{ $prefCommonClass }}">
                                                    @if(isset($companyInfo->securities->currency)){{ $companyInfo->securities->currency }}@endif {{ number_format($data->open, '2', ',', "'") }}
                                                </span>
                    </td>
                </tr>
        @endif
        @endforeach
        <tbody>
    </table>
</section>
