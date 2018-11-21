<?php
    
    namespace App\Http\Controllers;
    
    use App\Poiz\REST\RequestBridge;
    use App\Poiz\Traits\WorkerTrait;

    class StockController extends Controller {
        use WorkerTrait;
        /** @var string */
        private $INTRINO_BASE;
    
        /** @var string */
        private $INTRINO_API_KEY;
    
    
        /**
         * StockController constructor.
         */
        public function __construct() {
            $envData                = $this->getParsedEnvData();
            $this->INTRINO_BASE     = $envData->INTRINO_BASE;
            $this->INTRINO_API_KEY  = $envData->INTRINO_API_KEY;
        }
    
        public function fetchHighestPrices(){
            return view('stocks.highest-market-prices', [
                'pageTitle'     => 'Highest Prices',
                'dataSet'       => $this->getStockData(),
                'navRoutes'     => $this->getRoutesForMenuLinks(),
            ]);
        }
        
        public function fetchMarketOverview(){
            return view('stocks.market-overview', [
                'pageTitle'     => 'Highest Prices',
                'dataSet'       => $this->getStockData(),
                'navRoutes'     => $this->getRoutesForMenuLinks(),
            ]);
        
        }
        
        public function fetchStockOverview(){
            return view('stocks.stock-overview', [
                'pageTitle'     => 'Highest Prices',
                'dataSet'       => $this->getStockData(),
                'navRoutes'     => $this->getRoutesForMenuLinks(),
            ]);
        
        }
        
        private function getStockData(){
            $stockQuotesRequest = new RequestBridge($this->INTRINO_BASE, $this->INTRINO_API_KEY);
            $companyInfoRequest = new RequestBridge($this->INTRINO_BASE, $this->INTRINO_API_KEY);
            $stockQuotesPayload = $stockQuotesRequest->fetchStockQuotesFromInTriNo('NYSE', date('Y-m-d'), 1, 20);
    
            if(isset($stockQuotesPayload->data) && $stockQuotesPayload->data){
                //SELECT FROM ANOTHER IDENTIFIER : NASDAQ, NYSE, BATS, ARCX
                $stockQuote     = null;
                foreach ($stockQuotesPayload->data as $iKey=>&$stockQuote){
                    $companyInfo    = $companyInfoRequest->fetchCompanyInfoByTickerFromInTriNo($stockQuote->figi_ticker);
                    $security       = (isset($companyInfo->securities) && $companyInfo->securities) ? $companyInfo->securities[0] : null;
                    if($security){
                        $companyInfo->securities    = $security;
                    }
                    $stockQuote->companyInfo    = $companyInfo;
                }
            }
            usort($stockQuotesPayload->data, 'App\Http\Controllers\StockController::sortPayloadBySecurityType');
            return $stockQuotesPayload->data;
        }
    }
    
    
