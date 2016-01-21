<?php

namespace MailPlus\MailPlus\Controller\Adminhtml\Settings;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action {
	
	/**
	 *
	 * @var PageFactory
	 */
	protected $resultPageFactory;
	
	/**
	 *
	 * @param Context $context        	
	 * @param PageFactory $resultPageFactory        	
	 */
	public function __construct(Context $context, PageFactory $resultPageFactory) {
		parent::__construct ( $context );
		$this->resultPageFactory = $resultPageFactory;
	}
	
	/**
	 * Index action
	 *
	 * @return \Magento\Backend\Model\View\Result\Page
	 */
	public function execute() {
		/** @var \Magento\Backend\Model\View\Result\Page $resultPage */
		$resultPage = $this->resultPageFactory->create ();
		$resultPage->setActiveMenu ( 'MailPlus_MailPlus::settings' );
		$resultPage->addBreadcrumb ( __ ( 'MailPlus Connector Settings' ), __ ( 'MailPlus Connector Settings' ) );
		$resultPage->getConfig ()->getTitle ()->prepend ( __ ( 'MailPlus Connector' ) );
		
		return $resultPage;
	}
	
	/**
	 *
	 * @return bool
	 */
	protected function _isAllowed() {
		return $this->_authorization->isAllowed ( 'MailPlus_MailPlus::settings' );
	}
}