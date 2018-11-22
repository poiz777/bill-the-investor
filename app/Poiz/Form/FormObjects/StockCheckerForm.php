<?php

	namespace App\Poiz\Form\FormObjects;
	
    use App\Poiz\Form\FormObjects\PzFormInterFace;
    use App\Poiz\Form\Helpers\FormHelper;
    
    
    /**
     * PzColorsForm
     **/
    class StockCheckerForm implements PzFormInterFace {
        use FormHelper;
        
        /**
         * @var array
         */
        protected $entityBank	= array();


		/**
		 * @var string
         *
         * ##FormLabel Stock Market Symbol
         * ##FormFieldHint <span class='pz-hint'><span class='fa fa-info'></span> &nbsp;The Stock Market Symbol (EG - NYSE: for New York Stock Exchange) <br /><span style='color:darkred'>Note: This is Case-Sensitive!</span></span>
         * ##FormInputType text
         * ##FormInputRequired 1
         * ##FormPlaceholder NYSE
         * ##FormInputOptions NULL
         * ##FormValidationStrategy VALIDATION_STRATEGY_STOCK_SYMBOL
         */
		protected $symbol;

		/**
		 * @var string
         *
         * ##FormLabel Quote Date
         * ##FormFieldHint <span class='pz-hint'>The Date for which to fetch the Quotes</span>
         * ##FormInputType datetime-local
         * ##FormInputRequired 0
         * ##FormPlaceholder 0
         * ##FormInputFixedValue CURRENT_DATETIME_LOCAL
         * ##FormValidationStrategy VALIDATION_STRATEGY_PASS_THROUGH
         */
		protected $quote_date;


		public function __construct(){
			$this->initializeEntityBank();
		}
    
        /**
         * @return string|mixed
         */
        public function getSymbol(): ?string {
            return $this->symbol;
        }
    
        /**
         * @return string|mixed
         */
        public function getQuoteDate(): ?string {
            return $this->quote_date;
        }
    
        
        
        
        /**
         * @param string|mixed $symbol
         *
         * @return StockCheckerForm
         */
        public function setSymbol( ?string $symbol ): StockCheckerForm {
            $this->symbol = $symbol;
        
            return $this;
        }
    
        /**
         * @param string|mixed $quote_date
         *
         * @return StockCheckerForm
         */
        public function setQuoteDate( ?string $quote_date ): StockCheckerForm {
            $this->quote_date = $quote_date;
        
            return $this;
        }
	}
