<?php

/**
 * 
 */
namespace MailPlus\MailPlus\Controller\Image;

use MailPlus\MailPlus\Helper\Data;
use Magento\Store\Model\ScopeInterface;

class Get extends \Magento\Framework\App\Action\Action {
	
	const FORMAT_NORMAL = 'n';
	const FORMAT_LARGE = 'l';
		
	/**
	 * @var \Magento\Catalog\Helper\Image
	 */
	protected $_imageHelper;
	
	/**
	 * @var \Magento\Framework\Controller\Result\ForwardFactory
	 */
	protected $_forwardFactory;
	
	/**
	 * @var \Magento\Framework\Controller\Result\RedirectFactory
	 */
	protected $_redirectFactory;
	
	/**
	 * @var \Magento\Catalog\Api\ProductRepositoryInterface
	 */
	protected $_productRepository;
	
	/**
	 * @var \Magento\Framework\App\Config\ScopeConfigInterface 
	 */
	protected $_scopeConfig;
	
	/**
	 * @param \Magento\Framework\App\Action\Context $context
	 * @param \Magento\Catalog\Helper\Image $imageHelper
	 * @param \Magento\Framework\Controller\Result\ForwardFactory $forwardFactory
	 * @param \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
	 * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
	 * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
	 */
	public function __construct(\Magento\Framework\App\Action\Context $context,
			\Magento\Catalog\Helper\Image $imageHelper,
			\Magento\Framework\Controller\Result\ForwardFactory $forwardFactory,
			\Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
			\Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
			\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig) {
		parent::__construct($context);
		
		$this->_forwardFactory = $forwardFactory;
		$this->_redirectFactory = $redirectFactory;
        $this->_imageHelper = $imageHelper;
        $this->_productRepository = $productRepository;
        $this->_scopeConfig = $scopeConfig;
    }
	
    public function handleNotFound() {
    	return $this->_forwardFactory->create()->forward('noroute');
    }
    
	public function execute() {
		$productId = $this->getRequest()->getParam('id');
		if (empty($productId)) {
			return $this->handleNotFound();
		}
		
		$product = $this->_productRepository->getById($productId);
		if (!$product) {
			return $this->handleNotFound();
		}

		$format = $this->getRequest()->getParam('f');
		if (empty($format)) {
			$format = self::FORMAT_NORMAL;
		}
		
		$storeId = $product->getStoreId();
		$width = 145;
		$height = 145;
		switch ($format) {
			case self::FORMAT_NORMAL :
				$width = $this->_scopeConfig->getValue(Data::CONFIG_SMALL_IMAGE_WIDTH, ScopeInterface::SCOPE_STORE, $storeId);
				$height = $this->_scopeConfig->getValue(Data::CONFIG_SMALL_IMAGE_HEIGHT, ScopeInterface::SCOPE_STORE, $storeId);
				break;
			CASE self::FORMAT_LARGE :
				$width = $this->_scopeConfig->getValue(Data::CONFIG_LARGE_IMAGE_WIDTH, ScopeInterface::SCOPE_STORE, $storeId);
				$height = $this->_scopeConfig->getValue(Data::CONFIG_LARGE_IMAGE_HEIGHT, ScopeInterface::SCOPE_STORE, $storeId);
				break;
		}
				
		$keepFrame = 1 == $this->_scopeConfig->getValue(Data::CONFIG_IMAGE_FORMAT, ScopeInterface::SCOPE_STORE, $storeId);
		$image = $this->_imageHelper->init($product, 'product_page_image_medium');		
		$url = $image->keepFrame($keepFrame)->resize($width, $height)->getUrl();
		return $this->_redirectFactory->create()->setUrl($url);
	}
}