<?php

namespace MailPlus\MailPlus\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper {
	const CONFIG_ENABLED = 'mailplus/general/enabled';
	const CONFIG_CONSUMER_KEY = 'mailplus/general/consumer_key';
	const CONFIG_CONSUMER_SECRET = 'mailplus/general/consumer_secret';
	
	/**
	 *
	 * @var \Magento\Framework\App\Config\ScopeConfigInterface
	 */
	protected $_scopeConfig;
	
	/**
	 *
	 * @param Context $context        	
	 * @param ScopeConfigInterface $scopeConfig        	
	 */
	public function __construct(Context $context, ScopeConfigInterface $scopeConfig) {
		parent::__construct ( $context );
		$this->_scopeConfig = $scopeConfig;
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
}