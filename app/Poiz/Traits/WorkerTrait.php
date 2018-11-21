<?php
    
    namespace App\Poiz\Traits;

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
        
    }
