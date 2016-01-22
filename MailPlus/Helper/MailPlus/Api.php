<?php

namespace MailPlus\MailPlus\Helper\MailPlus;

class Api extends \Magento\Framework\App\Helper\AbstractHelper {
	const MP_API_HOST = 'https://restapi.mailplus.nl';
	const API_CONTACT_PROPERTIES_LIST = '/integrationservice-1.1.0/contact/properties/list';
	
	
	protected $CONSUMER_KEY;
	protected $CONSUMER_SECRET;
	protected $LOG_REQUESTS;
	
	
	public function __construct($consumerKey, $consumerSecret, $logRequests = false) {
		$this->CONSUMER_KEY = $consumerKey;
		$this->CONSUMER_SECRET = $consumerSecret;
		$this->LOG_REQUESTS = $logRequests;
	}
	private function getRestClient() {
		$configOauth = array (
				// 'callbackUrl' => Mage::getUrl('*/*/callback'),
				// 'siteUrl' => $restBaseDomain, // no need for 2-way
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
		
		return $client;
	}
	
	public function getContactProperties() {
		$client = $this->getRestClient();
		$response = $client->restGet( self::API_CONTACT_PROPERTIES_LIST );

		if ($response->getStatus() == 200) {
			return json_decode( $response->getBody() );
		}
		
		return null;
	}
} 