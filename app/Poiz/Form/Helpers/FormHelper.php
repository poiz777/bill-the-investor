<?php
	namespace App\Poiz\Form\Helpers;


	trait FormHelper {
        
        public function initializeEntityBank(){
            $refClass					= new \ReflectionClass($this);
            
            foreach ($refClass->getProperties() as &$refProperty) {
                $key					= $refProperty->getName();
                $this->entityBank[$key]	= $this->$key;
            }
            return $this->entityBank;
        }

		public function autoSetClassProps($props){
			if( (is_array($props) || is_object($props)) && $props ){
				foreach($props as $propName=>$propValue){
					$gsName                     = $this->rinseFieldName($propName);
					$setterMethod               = "set" . $gsName;
					if(property_exists($this, $propName)){
						if(method_exists($this, $setterMethod)){
							$this->$setterMethod($propValue);
						}else if(property_exists($this, $propName)){
							$this->$propName			= $propValue;
						}
						$this->entityBank[$propName]	= $propValue;
					}
				}
			}
		}

		public function initializeProperties($object){
			foreach ($object as $prop=>$propVal) {
				if(property_exists($this, $prop)){
					if($prop == "entityBank" || preg_match("#^_#", $prop)){ continue; }
					$this->$prop				= $propVal;
					$this->entityBank[$prop]	= $propVal;
				}
			}
			return $this;
		}

		protected function getClassProperties($fullyQualifiedClassName){
			$arrClassProps                  = [];
			$refClass                       = new \ReflectionClass($fullyQualifiedClassName);

			foreach ($refClass->getProperties() as &$refProperty) {
				$arrClassProps[]        = $refProperty->getName();
			}
			return $arrClassProps;
		}

		public function objectToArrayRecursive($object, &$return_array=null){
			if(!is_object($object) || empty($object)) return null;
			$return_array = (!$return_array) ? [] : $return_array;
			foreach($object as $key=>$val){
				if(is_object($val)){
					$return_array[$key] = [];
					$this->objectToArrayRecursive($val, $return_array[$key]);
				}else{
					$return_array[$key]		= $val;
				}
			}
			return $return_array;
		}

		public function arrayToObjectRecursive($array, &$objReturn=null){
			if(!is_array($array) || empty($array)) return null;
			$objReturn = (!$objReturn) ? new \stdClass() : $objReturn;
			foreach($array as $key=>$val){
				if(is_array($val)){
					$objReturn->$key = new \stdClass();
					$this->arrayToObjectRecursive($val, $objReturn->$key);
				}else{
					$objReturn->$key		= $val;
				}
			}
			return $objReturn;
		}

		public function getEntityBank() {
			return $this->entityBank;
		}

		public static function cmp($a, $b){
			return $a->name > $b->name;
		}

        public function generateRandomHash($length = 6) {
            $characters     = '0123456789ABCDEF';
            $randomString   = '';

            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }

            return $randomString;
        }
        
        protected function rinseFieldName($fieldName){
            $arrName    = preg_split("#[_\-\s]+#",    $fieldName);
            $arrName    = array_map("ucfirst", $arrName);;
            $strName    = implode("", $arrName);
            return $strName;
        }

		public static function screenOffErrorsWithIDWithinKeys(array $arrErrors) {
			$arrScreenedErrors = array();
			foreach ($arrErrors as $errorKey => $strErrorMessage) {
				if (preg_match('#(^id)|(.*Id$)#', $errorKey)) {
					continue;
				}
				else {
					$arrScreenedErrors[$errorKey] = $strErrorMessage;
				}
			}
			return $arrScreenedErrors;
		}

		

        public function __get($name) {
            if(property_exists($this, $name)){
                return $this->$name;
            }else{
                if(array_key_exists($name, $this->entityBank)){
                    return $this->entityBank[$name];
                }
            }
            return null;
        }

        public function __set($name, $value) {
            if(property_exists($this, $name)){
                $this->$name     = $value;
                if($name == 'entityBank'){
                    if(!empty($value)){
                        $this->autoSetClassProps($value);
                    }
                }else{
                    $this->entityBank[$name]	= $value;
                }
            }else{
                $this->entityBank[$name]		= $value;
            }
            return $this;
        }

    }
