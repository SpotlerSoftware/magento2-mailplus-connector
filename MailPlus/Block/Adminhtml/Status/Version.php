<?php

namespace MailPlus\MailPlus\Block\Adminhtml\Status;

use Magento\Backend\Block\Template;

class Version extends Template {
	
	/**
	 *
	 * @param Context $context        	
	 * @param array $data        	
	 */
	public function __construct(Template\Context $context, array $data = []) {
		parent::__construct ( $context, $data );
	}
	
	public function getModuleVersion() {
		return "0.0.1 beta";
	}
	
}