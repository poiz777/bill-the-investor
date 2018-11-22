<?php
    
    namespace App\Poiz\Traits;

    use App\Poiz\Form\FormObjects\PzFormInterFace;
    use App\Poiz\Form\Helpers\FormBaker;
    use Symfony\Component\HttpFoundation\Request;

    trait WorkerTrait {
        
        private function getParsedEnvData(){
            $envFile    = base_path() . '/.env';
            return $this->parseEnvFile($envFile);
        }
    
        private static function sortPayloadBySecurityType($prevData, $nextData){
            if(isset($prevData->companyInfo->securities->security_type) && isset($nextData->companyInfo->securities->security_type)){
                return $prevData->companyInfo->securities->security_type < $nextData->companyInfo->securities->security_type;
            }
            return  false;
        }
    
        private static function sortPayloadByExchangeSymbol($prevData, $nextData){
            if(isset($prevData->companyInfo->securities->exch_symbol) && isset($nextData->companyInfo->securities->exch_symbol)){
                return $prevData->companyInfo->securities->exch_symbol < $nextData->companyInfo->securities->exch_symbol;
            }
            return  false;
        }
    
        private function getRoutesForMenuLinks(){
            return [
                'Get Quotes'                => 'rte.stock_checker.get',
                'Highest Market Prices'     => 'rte.stocks.highest_market_prices.get',
                'Market Overview'           => 'rte.stocks.market_overview.get',
                'Company Stock Overview'    => 'rte.stocks.stock_overview.get',
            ];
        }
    
        private function parseEnvFile($envFile){
            $envData            =  file_get_contents($envFile);
            $envObject          = new \stdClass();
            if($envData){
                $envData        = explode("\n", $envData);
                foreach($envData as $intDex=>$data){
                    $tmpData    = explode("=", $data);
                    if(count($tmpData)>1){
                        $key    = trim($tmpData[0]);
                        $envObject->$key = trim($tmpData[1]);
                    }
                }
            }
            return $envObject;
        
        }
    
        private function getFormOptions($name='stock_checker_form', $btnLabel='Submit', $action=''){
            return [
                'name'      => $name,
                'btnID'     => 'pz-submit-button',
                'class'     => 'form-horizontal',
                'method'    => 'POST',
                'action'    => $action,
                'enctype'   => 'multipart/form-data',
                'btnClass'  => 'form-control pz-submit-button',
                'btnLabel'  => $btnLabel,
            ];
        }
    
        protected function filterFormData(Request $request, $formKey='stockcheckerform'){
            $formData           = $request->request->get($formKey);
            if($formData){
                foreach($formData as $field=>&$datum){
                    if(strstr($field, 'date')){
                        $datum  = (new \DateTime($datum))->format('Y-m-d');
                    }
                }
                return $formData;
            }
            return $formData;
        }
    
        protected function validateFormData(Request $request, FormBaker $formBakerInstance, PzFormInterFace $itemFormObject, $formKey, $rayFormItemOptions=[]){
            // VALIDATION:
            $validationData             = new \stdClass();
            $validationData->rayForm    = null;
            $rayErrors                  = null;
            $strErrors                  = null;
        
            $itemFormObject->autoSetClassProps($this->filterFormData($request, $formKey));
            try {
                $rayValidityBag = $formBakerInstance->validate( $itemFormObject );
                $rayErrors      = $rayValidityBag['errors'];
                $raySanitized   = $rayValidityBag[$formKey];
                $strErrors      = $formBakerInstance->renderErrors($rayErrors, true);
                $itemFormObject->autoSetClassProps($raySanitized);
            
                $validationData->rayErrors      = $rayErrors;
                $validationData->raySanitized   = $raySanitized;
                $validationData->strErrors      = $strErrors;
                $validationData->rayValidityBag = $rayValidityBag;
                $rayItemForm                    = $formBakerInstance->buildFormFromEntityClass( $rayFormItemOptions, true, $itemFormObject );
                $validationData->rayForm        = $rayItemForm;
            } catch ( \ReflectionException $e ) { }
        
            if($rayErrors){
                $formBakerInstance->setErrorFields($rayErrors);
                try {
                    $rayItemForm = $formBakerInstance->buildFormFromEntityClass( $rayFormItemOptions, true, $itemFormObject );
                    $validationData->rayForm    = $rayItemForm;
                } catch ( \ReflectionException $e ) {}
            }
            return $validationData;
        }
    
    
    }
