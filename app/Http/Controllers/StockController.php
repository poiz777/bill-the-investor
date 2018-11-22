<?php
    
    namespace App\Http\Controllers;
    
    use App\Poiz\Form\FormObjects\StockCheckerForm;
    use App\Poiz\Form\Helpers\FormBaker;
    use App\Poiz\REST\RequestBridge;
    use App\Poiz\Traits\WorkerTrait;
    use Symfony\Component\HttpFoundation\Request;

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
    
        public function fetchHighestPrices(Request $request){
            $quoteDate          = ($request->session()->has('quoteDate'))    ? $request->session()->get('quoteDate')     : date('Y-m-d');
            $identifier         = ($request->session()->has('brokerSymbol')) ? $request->session()->get('brokerSymbol')  : 'NYSE';
            return view('stocks.highest-market-prices', [
                'pageTitle'     => 'Highest Market Prices',
                'quoteDate'     => (new \DateTime($quoteDate))->format('d.m.Y'),
                'dataSet'       => $this->getStockData($quoteDate, $identifier),
                'navRoutes'     => $this->getRoutesForMenuLinks(),
            ]);
        }
        
        public function fetchMarketOverview(Request $request){
            $quoteDate          = ($request->session()->has('quoteDate'))    ? $request->session()->get('quoteDate')     : date('Y-m-d');
            $identifier         = ($request->session()->has('brokerSymbol')) ? $request->session()->get('brokerSymbol')  : 'NYSE';
           
            return view('stocks.market-overview', [
                'pageTitle'     => 'Market Overview',
                'quoteDate'     => (new \DateTime($quoteDate))->format('d.m.Y'),
                'dataSet'       => $this->getStockData($quoteDate, $identifier),
                'navRoutes'     => $this->getRoutesForMenuLinks(),
            ]);
        
        }
        
        public function fetchStockOverview(Request $request){
            $quoteDate          = ($request->session()->has('quoteDate'))    ? $request->session()->get('quoteDate')     : date('Y-m-d');
            $identifier         = ($request->session()->has('brokerSymbol')) ? $request->session()->get('brokerSymbol')  : 'NYSE';
            return view('stocks.stock-overview', [
                'pageTitle'     => 'Company Stock Overview',
                'quoteDate'     => (new \DateTime($quoteDate))->format('d.m.Y'),
                'dataSet'       => $this->getStockData($quoteDate, $identifier),
                'navRoutes'     => $this->getRoutesForMenuLinks(),
            ]);
        
        }
        
        private function getStockData($quoteDate, $identifier='NYSE'){
            $stockQuotesRequest = new RequestBridge($this->INTRINO_BASE, $this->INTRINO_API_KEY);
            $companyInfoRequest = new RequestBridge($this->INTRINO_BASE, $this->INTRINO_API_KEY);
            $stockQuotesPayload = $stockQuotesRequest->fetchStockQuotesFromInTriNo($identifier, $quoteDate, 1, 20);
    
            if(isset($stockQuotesPayload->data) && $stockQuotesPayload->data){
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
    
        public function stockQuotes(Request $request){
            $formKey            = 'stockcheckerform';
            $objStockForm       = new StockCheckerForm();
            $stockFormFormBaker = new FormBaker(StockCheckerForm::class);
            $rayFormOptions     = $this->getFormOptions('stock_checker_form', 'Fetch Quotes');
            $formData           = $this->filterFormData($request, $formKey);
            $strErrors          = null;
        
            if($formData){
                // VALIDATION:
                $validationData = $this->validateFormData($request,  $stockFormFormBaker, $objStockForm, $formKey, $rayFormOptions);
                if($validationData->rayErrors){
                    // RENDER FORM WITH ERRORS...
                    return view('stocks.stock-checker', [
                        'pageTitle'     => 'Check Stocks',
                        'stockForm'     => $validationData->rayForm,
                        'pzErrors'      => $validationData->strErrors,
                        'navRoutes'     => $this->getRoutesForMenuLinks(),
                    ]);
                }
    
                $request->session()->put('quoteDate',      $validationData->raySanitized['quote_date']);
                $request->session()->put('brokerSymbol',   $validationData->raySanitized['symbol']);
                return $this->fetchMarketOverview($request);
            }
            
            // FORM RENDERING:
            $rayStockForm       = $stockFormFormBaker->buildFormFromEntityClass( $rayFormOptions, true, $objStockForm );
    
            return view('stocks.stock-checker', [
                'pageTitle'     => 'Check Stocks',
                'stockForm'     => $rayStockForm,
                'pzErrors'      => $strErrors,
                'navRoutes'     => $this->getRoutesForMenuLinks(),
            ]);
        }
    }
    
    
