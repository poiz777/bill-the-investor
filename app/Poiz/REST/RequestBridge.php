<?php

namespace App\Poiz\REST;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\RequestOptions;

/**
 * Class RequestBridge
 * @package App\Poiz\REST
 */
class RequestBridge {
	/**
	 * @var string
	 */
	private $endPoint;
	
	/**
	 * @var string
	 */
	private $apiKey;
	
	/**
	 * @var \GuzzleHttp\Client
	 */
	private $client;
	
	/**
	 * @var string
	 */
	private $baseURI;
	
	/**
	 * @var string
	 */
	private $requestMethod;
	
	/**
	 * @var
	 */
	private $requestURI;
	
	/**
	 * @var string
	 */
	private $returnFormat;
	
	/**
	 * @var string
	 */
	private $restFunction;
	
	/**
	 * @var string
	 */
	private $restBaseURI;
	
	/**
	 * @var string
	 */
	private $symbol;
	
	/**
	 * @var array
	 */
	private $defaultHeader;

	/**
	 * RequestBridge constructor.
	 *
	 * @param string $endPoint          : THE ENDPOINT HERE IS THE PART THAT STARTS WITH "v1". EG: "v1/meta/lists"
	 * @param string $restBaseURI       : THE BASE URI FOR REST-BASED OPERATIONS....
	 * @param string $apiKey            : THIS IS THE API KEY REQUIRED FOR AUTHENTICATION.
	 * @param string $symbol            : THIS IS THE SYMBOL FOR THE STOCK COMPANY IN QUESTION.
	 * @param string $restFunction      : THIS IS THE REQUIRED, FUNCTION PART OF THE QUERY PARAMETER(S).
	 * @param string $requestMethod     : THE REQUEST-METHOD - USUALLY HTTP (REST) VERBS LIKE: GET, PUT, POST, DELETE, ETC
	 * @param array|object|mixed $data  : ANY PAYLOAD NEEDED TO BE SENT ALONG WITH THE REQUEST: FOR 'POST', 'PUT' OR SIMILAR OPERATIONS
	 */
	public function __construct($restBaseURI, $apiKey, $endPoint=null, $restFunction=null,  $symbol=null, $requestMethod='GET', $data=null) {
		$this->endPoint         = $endPoint;
		$this->restBaseURI      = $restBaseURI;
		$this->restFunction     = $restFunction;
		$this->apiKey           = $apiKey;
		$this->symbol           = $symbol;
		$this->client           = new Client();
		$this->baseURI          = $this->restBaseURI;
		$this->requestMethod    = "GET";
		$this->returnFormat     = "PHP";
		$this->defaultHeader    = ['Accept-Charset'=>'UTF-8', 'Accept'=>'application/json'];
		
		// SET THE FULL, REQUEST URI: USEFUL ONLY FOR DEBUGGING PURPOSES - TO SEE THE FULL URI
		$this->setRequestURI($this->baseURI . $this->endPoint);
	}

	/**
	 * FETCH A RESOURCE FROM REST API USING GUZZLE
	 * IF THE CHOSEN RESPONSE-TYPE IS 'PHP', THE PAYLOAD WILL BE CONVERTED TO A PHP OBJECT
	 * OTHERWISE, A \Psr\Http\Message\StreamInterface OBJECT WILL BE RETURNED.
	 * @return mixed|null|\Psr\Http\Message\StreamInterface
	 */
	public function fetchResource(){
		try {
			$this->requestURI   = $this->baseURI . $this->endPoint;
			$header             = array_merge($this->defaultHeader, $this->getHeaderByReturnFormat());
			$response           = $this->client->request( $this->requestMethod, $this->requestURI, ['headers' => $header] );
			$responseData       = $response->getBody();
			$lcFormat           = strtolower($this->returnFormat);
			return ($lcFormat == 'php' || $lcFormat == 'phpobject') ? json_decode($responseData) : $responseData->getContents();
		} catch ( GuzzleException $e ) {
			# TODO: HANDLE EXCEPTION HERE...
		}
		return null;
	}
	
	/**
	 * GIVEN AN IDENTIFIER (EX. NEW YORK STOCK EXCHANGE[NYSE]), FETCHES STOCK QUOTES USING THE INTRINO API
	 * @param string    $identifier     : EXAMPLE - NYSE, ETC
     * @param string    $priceDate      : THE SPECIFIC DATE OF THE QUOTES TO FETCH
	 * @param int       $pageNum        : THE PAGE NUMBER, DEFAULT IS 1 - FIRST PAGE
	 * @param int       $pageSize       : THE NUMBER OF RETURNED RECORDS PER PAGE (WE NEED IT AS WE DON'T WANT THOUSANDS OF DATA-SETS)
	 * @return mixed|null|\Psr\Http\Message\StreamInterface
	 */
	public function fetchStockQuotesFromInTriNo($identifier='NYSE', $priceDate=null,  $pageNum=1, $pageSize=20){
	    // WE WANT CURRENT UPDATES RATHER THAN A PAYLOAD OF HISTORICAL DATA... SO WE ENFORCE THE USE OF DATE PARAMETER
        // IF NONE COMES IN, WE DEFAULT TO THE CURRENT DATE..... HOPEFULLY, WE MIGHT HAVE SOME UPDATES AT THE TIME OF THE REQUEST.
	    $priceDate          = !$priceDate ? date("Y-m-d", time()) : $priceDate;
	    
        $this->setEndPoint("/prices/exchange?identifier={$identifier}&price_date={$priceDate}&page_size={$pageSize}&page_number={$pageNum}&api_key={$this->apiKey}");
        
        // PERFORM THE REQUEST & FETCH THE NEEDED RESOURCE(S)
        $payload            = $this->fetchResource();
        
        // RETURN THE PAYLOAD
        return $payload;
	}
	
	/**
	 * GIVEN A TICKER (EX. AAPL), FETCHES COMPANY INFORMATION USING THE INTRINO API
	 * @param string    $ticker         : EXAMPLE - AAPL, ETC
	 * @return mixed|null|\Psr\Http\Message\StreamInterface
	 */
	public function fetchCompanyInfoByTickerFromInTriNo($ticker){
        $this->setEndPoint("/companies?ticker={$ticker}&api_key={$this->apiKey}");
        
        // PERFORM THE REQUEST & FETCH THE NEEDED RESOURCE(S)
        $payload            = $this->fetchResource();
        
        // RETURN THE PAYLOAD
        return $payload;
	}
	
	/**
	 * FETCHES STOCK EXCHANGE LISTS & INFORMATION FOR ALL STOCK EXCHANGES COVERED BY INTRINO
	 * @return mixed|null|\Psr\Http\Message\StreamInterface
	 */
	public function fetchStockExchangeListFromInTriNo(){
        $this->setEndPoint("/stock_exchanges?&api_key={$this->apiKey}");
        
        // PERFORM THE REQUEST & FETCH THE NEEDED RESOURCE(S)
        $payload            = $this->fetchResource();
        
        // RETURN THE PAYLOAD
        return $payload;
	}

	/**
	 * GETS THE REQUEST HEADER THAT WILL BE MERGED WITH THE DEFAULT FOR THE API REQUEST
	 * @return array
	 */
	private function getHeaderByReturnFormat(){
		switch(strtolower($this->returnFormat)){
			case 'php':
			case 'json':
			case 'phpobject':
				$format = ['Accept' => 'application/json'];
				break;
			case 'xml':
				$format = ['Accept' => 'text/xml'];
				break;
			case 'csv':
				$format = ['Accept' => 'text/csv'];
				break;
			case 'txt':
			case 'text':
				$format = ['Accept' => 'text/plain'];
				break;
			case 'bin':
			case 'image':
			case 'photo':
			case 'binary':
				$format = ['Accept' => 'application/octet-stream'];
				break;
			default:
				$format = ['Accept' => 'application/json'];
				break;
		}
		return $format;
	}
    
    
    
	
    
    /**
     * USING THE OPEN FIGI API, FETCHES DATA THAT MAPS FIGI TICKERS TO FAMILIAR SYMBOLS & NAMES
     * @param string    $symbol
     * @return mixed|null|\Psr\Http\Message\StreamInterface
     */
    public function mapFIGIData($symbol){
        $response           = $this->client->post($this->baseURI, [
            'headers'       => array_merge($this->defaultHeader, ['X-OPENFIGI-APIKEY' => $this->apiKey]),
            RequestOptions::JSON => [
                [
                    'idType'    => 'TICKER',
                    'idValue'   => $symbol
                ]
            ]
        ]);
        $responseData       = $response->getBody();
        $lcFormat           = strtolower($this->returnFormat);
        return ($lcFormat == 'php' || $lcFormat == 'phpobject') ? json_decode($responseData) : $responseData->getContents();
    }
    
    /**
     * FETCHES STOCK DATA BY SYMBOL USING THE ALPHA VANTAGE API
     * @param string    $symbol
     * @param string    $function
     *
     * @return mixed|null|\Psr\Http\Message\StreamInterface
     */
    public function fetchStockDataBySymbol($symbol, $function){
        $initialEndPoint    = $this->getEndPoint();
        // RESET THE ENDPOINT FOR THE PURPOSES OF THIS OPERATION.
        $this->setEndPoint("?function={$function}&symbol={$symbol}&apikey={$this->apiKey}");
        
        // PERFORM THE REQUEST & FETCH THE NEEDED RESOURCE(S)
        $payload            = $this->fetchResource();
        
        // REVERT THE ENDPOINT TO THE ORIGINAL/INITIAL VALUE TO AVOID CONFLICTS DURING SUBSEQUENT REQUESTS
        $this->setEndPoint($initialEndPoint);
        
        // RETURN THE PAYLOAD
        return $payload;
    }


	/**
	 * @return string
	 */
	public function getEndPoint(): ?string {
		return $this->endPoint;
	}

	/**
	 * @return string
	 */
	public function getApiKey(): ?string {
		return $this->apiKey;
	}

	/**
	 * @return Client
	 */
	public function getClient(): Client {
		return $this->client;
	}

	/**
	 * @return string
	 */
	public function getRequestMethod(): string {
		return $this->requestMethod;
	}

	/**
	 * @return string
	 */
	public function getReturnFormat(): string {
		return $this->returnFormat;
	}

	/**
	 * @return array
	 */
	public function getDefaultHeader(): array {
		return $this->defaultHeader;
	}

	/**
	 * @return string
	 */
	public function getBaseURI(): string {
		return $this->baseURI;
	}

	/**
	 * @return string
	 */
	public function getRequestURI() {
		return $this->requestURI;
	}

	

	/**
	 * @param string $endPoint
	 */
	public function setEndPoint( ?string $endPoint ): void {
		$this->endPoint = $endPoint;
	}

	/**
	 * @param string $apiKey
	 */
	public function setApiKey( ?string $apiKey ): void {
		$this->apiKey = $apiKey;
	}

	/**
	 * @param Client $client
	 */
	public function setClient( Client $client ): void {
		$this->client = $client;
	}

	/**
	 * @param string $requestMethod
	 */
	public function setRequestMethod( string $requestMethod ): void {
		$this->requestMethod = $requestMethod;
	}

	/**
	 * @param string $returnFormat
	 *
	 * @return RequestBridge
	 */
	public function setReturnFormat( string $returnFormat ): RequestBridge {
		$this->returnFormat = $returnFormat;

		return $this;
	}

	/**
	 * @param string $baseURI
	 *
	 * @return RequestBridge
	 */
	public function setBaseURI( string $baseURI ): RequestBridge {
		$this->baseURI = $baseURI;

		return $this;
	}

	/**
	 * @param mixed $requestURI
	 *
	 * @return RequestBridge
	 */
	public function setRequestURI( $requestURI ) {
		$this->requestURI = $requestURI;

		return $this;
	}
}
