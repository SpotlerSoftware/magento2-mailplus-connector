<?php

namespace MailPlus\MailPlus\Controller\Adminhtml\Status;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action {
	
	/**
	 *
	 * @var PageFactory
	 */
	protected $resultPageFactory;
	
	private $_version;
	
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
		$resultPage->setActiveMenu ( 'MailPlus_MailPlus::status' );
		$resultPage->addBreadcrumb ( __ ( 'MailPlus Connector Status' ), __ ( 'MailPlus Connector Status' ) );
		
		return $resultPage;
	}
	
	/**
	 *
	 * @return bool
	 */
	protected function _isAllowed() {
		return $this->_authorization->isAllowed ( 'MailPlus_MailPlus::status' );
	}
}