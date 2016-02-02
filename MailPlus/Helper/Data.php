<?php

namespace MailPlus\MailPlus\Helper;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use MailPlus\MailPlus\Helper\MailPlusApi\ApiFactory;

class Data extends AbstractHelper {
	const CONFIG_ENABLED = 'mailplus/general/enabled';
	const CONFIG_CONSUMER_KEY = 'mailplus/general/consumer_key';
	const CONFIG_CONSUMER_SECRET = 'mailplus/general/consumer_secret';

	const CONFIG_SMALL_IMAGE_WIDTH = "mailplus/images/image_width_small";
	const CONFIG_SMALL_IMAGE_HEIGHT = "mailplus/images/image_height_small";
	const CONFIG_LARGE_IMAGE_WIDTH = "mailplus/images/image_width_large";
	const CONFIG_LARGE_IMAGE_HEIGHT = "mailplus/images/image_height_large";
	
	const CONFIG_IMAGE_FORMAT = "mailplus/images/image_format";
	
	const CONFIG_DEBUG_LOGGING = 'mailplus/advanced/logging';
	
	/**
	 *
	 * @var \Magento\Framework\App\Config\ScopeConfigInterface
	 */
	protected $_scopeConfig;
	
	/**
	 *	@var Collection
	 */
	protected $_productCollection;
	
	/**
	 * @var ApiFactory
	 */
	protected $_apiFactory;
	
	/**
	 *
	 * @param Context $context        	
	 * @param ScopeConfigInterface $scopeConfig        	
	 */
	public function __construct(Context $context, ScopeConfigInterface $scopeConfig,
			Collection $productCollection,
			/* A Factory is automaticly created when it does not exists! */
			ApiFactory $apiFactory) {
		parent::__construct ( $context );
		$this->_scopeConfig = $scopeConfig;
		$this->_productCollection = $productCollection;
		$this->_apiFactory = $apiFactory;
	}
	
	/**
	 * @return string
	 */
	public function getConsumerKey($storeId = null) {
		return $this->_scopeConfig->getValue(self::CONFIG_CONSUMER_KEY, ScopeInterface::SCOPE_STORE, $storeId);
	}
	
	public function isEnabledForStore($storeId) {
		return 1 == $this->_scopeConfig->getValue(self::CONFIG_ENABLED, ScopeInterface::SCOPE_STORE, $storeId);
	}
	
	public function isEnabledForSite($siteId) {
		return 1 == $this->_scopeConfig->getValue(self::CONFIG_ENABLED, ScopeInterface::SCOPE_WEBSITE, $siteId);
	}
	
	public function debugLogEnabled($siteId) {
		return 1 == $this->_scopeConfig->getValue(self::CONFIG_DEBUG_LOGGING, ScopeInterface::SCOPE_WEBSITE, $siteId);
	}
	
	/**
	 * 
	 * @param int $websiteId
	 * @return \MailPlus\MailPlus\Helper\MailPlusApi\Api
	 */
	public function getApiClient($websiteId) {
		$key = $this->_scopeConfig->getValue(self::CONFIG_CONSUMER_KEY, ScopeInterface::SCOPE_WEBSITE, $websiteId);
		$secret = $this->_scopeConfig->getValue(self::CONFIG_CONSUMER_SECRET, ScopeInterface::SCOPE_WEBSITE, $websiteId);

		if (empty($key) || empty($secret)) {
			return null;
		}
		// Use the automaticly created factory to create an Api client 
		return $this->_apiFactory->create(array(
				'consumerKey' => $key,
				'consumerSecret' => $secret,
				'logRequests' => false));
	}
	
	
	
}