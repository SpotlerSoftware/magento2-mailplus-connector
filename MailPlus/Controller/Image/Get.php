<?php

/**
 * 
 */
namespace MailPlus\MailPlus\Controller\Image;

class Get extends \Magento\Framework\App\Action\Action {
	
	/**
	 * @var \Magento\Catalog\Helper\Image
	 */
	protected $_imageHelper;
	
	/**
	 * @var \Magento\Framework\Controller\Result\ForwardFactory
	 */
	protected $_forwardFactory;
	
	public function __construct(\Magento\Framework\App\Action\Context $context,
			\Magento\Catalog\Helper\Image $imageHelper,
			\Magento\Framework\Controller\Result\ForwardFactory $forwardFactory) {
        $this->_forwardFactory = $forwardFactory;
        $this->_imageHelper = $imageHelper;
        parent::__construct($context);
    }
	
	public function execute() {
		
		
		
		die ("Image Get Action");
	}
}