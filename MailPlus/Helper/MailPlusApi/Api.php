<?php

namespace MailPlus\MailPlus\Helper\MailPlusApi;

use Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Helper\Context;

class Api extends AbstractHelper {
	const MP_API_HOST = 'https://restapi.mailplus.nl';
	const BASE_URI = '/integrationservice-1.1.0';
	const API_CONTACT_PROPERTIES_LIST = '/contact/properties/list';
	const API_PRODUCT = '/product';
	
	protected $CONSUMER_KEY;
	protected $CONSUMER_SECRET;
	
	protected $_productHelper;
	
	protected $_client = null;

	public function __construct(Context $context, $consumerKey, $consumerSecret, ProductHelper $productHelper) {
		$this->CONSUMER_KEY = $consumerKey;
		$this->CONSUMER_SECRET = $consumerSecret;
		
		$this->_productHelper = $productHelper;
		parent::__construct($context);
	}
	
	private function getRestClient() {
		if ($this->_client) {
			return $this->_client;
		}
		
		$configOauth = array (
				'requestScheme' => \Zend_Oauth::REQUEST_SCHEME_HEADER,
				'consumerKey' => $this->CONSUMER_KEY,
				'consumerSecret' => $this->CONSUMER_SECRET,
				'version' => '1.0',
				'signatureMethod' => 'HMAC-SHA1' 
		);
		
		$config = array (
				'useragent' => 'Magento2-MailPlus' 
		);
		
		$token = new TokenAccess();
		$httpClient = $token->getHttpClient( $configOauth, NULL, $config );
		$httpClient->setHeaders( 'Content-Type', 'application/json' );
		$httpClient->setHeaders( 'Accept', 'application/json' );
		$httpClient->setHeaders( 'Accept-encoding', '' );
		$httpClient->setKeepContentType();
		
		$client = new \Zend_Rest_Client( self::MP_API_HOST );
		$client->setHttpClient( $httpClient );
		
		$this->_client = $client;
		return $client;
	}
	
	public function getContactProperties() {
		$client = $this->getRestClient();
		$response = $client->restGet(self::BASE_URI . self::API_CONTACT_PROPERTIES_LIST);

		if ($response->getStatus() == 200) {
			return json_decode( $response->getBody() );
		}
		
		return null;
	}
	
	public function syncProduct($product) {
		$client = $this->getRestClient();
		$data = $this->_productHelper->getProductData($product);
		
		$client->restPost(self::BASE_URI . self::API_PRODUCT, json_encode($data));
	}
} 