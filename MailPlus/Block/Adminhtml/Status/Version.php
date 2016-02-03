<?php

namespace MailPlus\MailPlus\Block\Adminhtml\Status;

use Magento\Backend\Block\Template;
use Magento\Framework\Module\ModuleList;
use MailPlus\MailPlus\Helper\ConfigurationDataHelper;

class Version extends Template {
	
	private $_configuration;
	private $_version;

	/**
	 *
	 * @param Template\Context $context
	 * @param ConfigurationDataHelper $configuration
	 * @param ModuleList $moduleList
	 * @param array $data
	 */
	public function __construct(Template\Context $context, ConfigurationDataHelper $configuration, ModuleList $moduleList, array $data = []) {
		parent::__construct ( $context, $data );
		
		$this->_version = $moduleList->getOne('MailPlus_MailPlus')['setup_version'];
		
		$this->_configuration = $configuration;
	}
	
	public function _prepareLayout() {
		$this->pageConfig->getTitle()->set('MailPlus Connector M2 v' . $this->_version);	
		return parent::_prepareLayout();
	}
	
	public function getModuleVersion() {
		return $this->_version;
	}
	
	public function getConsumerKey() {
		return $this->_configuration->getConsumerKey();
	}
	
	public function getCurrentWebsite() {
		$websiteId = $this->getRequest()->getParam('website'); 
		if (isset($websiteId)) {
			return $this->_storeManager->getWebsite($websiteId);
		}
		
		return array_values($this->_storeManager->getWebsites())[0];
	}
	
	public function isEnabledForSite($siteId) {
		return $this->_configuration->isEnabledForSite($siteId);
	}
	
	public function checkMailPlusApi($websiteId) {
		/**
		 * 
		 * @var MailPlus\MailPlus\Helper\MailPlusApi\Api
		 */
		if(!$this->_configuration->isEnabledForSite($websiteId)){
			return false;
		}
		$api = $this->_configuration->getApiClient($websiteId);

		$props = $api->getContactProperties();

		if ($props) {
			return true;
		}
		return false;
	}
}