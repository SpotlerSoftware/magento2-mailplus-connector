<?php

namespace MailPlus\MailPlus\Block\Adminhtml\Status;

use Magento\Backend\Block\Template;

class Version extends Template {
	
	private $_dataHelper;
	private $_version;
	
	/**
	 *
	 * @param Template\Context $context
	 * @param \MailPlus\MailPlus\Helper\Data $dataHelper        	
	 * @param array $data        	
	 */
	public function __construct(Template\Context $context, \MailPlus\MailPlus\Helper\Data $dataHelper, \Magento\Framework\Module\ModuleList $moduleList, array $data = []) {
		parent::__construct ( $context, $data );
		
		$this->_version = $moduleList->getOne('MailPlus_MailPlus')['setup_version'];
		
		$this->_dataHelper = $dataHelper;
	}
	
	public function _prepareLayout() {
		$this->pageConfig->getTitle()->set('MailPlus Connector M2 v' . $this->_version);	
		return parent::_prepareLayout();
	}
	
	public function getModuleVersion() {
		return $this->_version;
	}
	
	public function getConsumerKey() {
		return $this->_dataHelper->getConsumerKey();
	}
	
	public function getCurrentWebsite() {
		$websiteId = $this->getRequest()->getParam('website'); 
		if (isset($websiteId)) {
			return $this->_storeManager->getWebsite($websiteId);
		}
		
		return array_values($this->_storeManager->getWebsites())[0];
	}
	
	public function isEnabledForSite($siteId) {
		return $this->_dataHelper->isEnabledForSite($siteId);
	}
	
	public function checkMailPlusApi($websiteId) {
		/**
		 * 
		 * @var MailPlus\MailPlus\Helper\MailPlus\Api
		 */
		$api = $this->_dataHelper->getApiClient($websiteId);
		$props = $api->getContactProperties();

		if ($props) {
			return true;
		}
		return false;
	}
}