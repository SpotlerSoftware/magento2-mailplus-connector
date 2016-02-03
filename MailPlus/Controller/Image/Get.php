<?php

/**
 * 
 */
namespace MailPlus\MailPlus\Controller\Image;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use MailPlus\MailPlus\Helper\ConfigurationDataHelper;
use Magento\Store\Model\ScopeInterface;

class Get extends Action {
	
	const FORMAT_NORMAL = 'n';
	const FORMAT_LARGE = 'l';
		
	/**
	 * @var Image
	 */
	protected $_imageHelper;
	
	/**
	 * @var ForwardFactory
	 */
	protected $_forwardFactory;
	
	/**
	 * @var RedirectFactory
	 */
	protected $_redirectFactory;
	
	/**
	 * @var ProductRepositoryInterface
	 */
	protected $_productRepository;
	
	/**
	 * @var ScopeConfigInterface
	 */
	protected $_scopeConfig;
	
	/**
	 * @param Context $context
	 * @param Image $imageHelper
	 * @param ForwardFactory $forwardFactory
	 * @param RedirectFactory $redirectFactory
	 * @param ProductRepositoryInterface $productRepository
	 * @param ScopeConfigInterface $scopeConfig
	 */
	public function __construct(Context $context,
								Image $imageHelper,
								ForwardFactory $forwardFactory,
								RedirectFactory $redirectFactory,
								ProductRepositoryInterface $productRepository,
								ScopeConfigInterface $scopeConfig) {
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
				$width = $this->_scopeConfig->getValue(ConfigurationDataHelper::CONFIG_SMALL_IMAGE_WIDTH, ScopeInterface::SCOPE_STORE, $storeId);
				$height = $this->_scopeConfig->getValue(ConfigurationDataHelper::CONFIG_SMALL_IMAGE_HEIGHT, ScopeInterface::SCOPE_STORE, $storeId);
				break;
			CASE self::FORMAT_LARGE :
				$width = $this->_scopeConfig->getValue(ConfigurationDataHelper::CONFIG_LARGE_IMAGE_WIDTH, ScopeInterface::SCOPE_STORE, $storeId);
				$height = $this->_scopeConfig->getValue(ConfigurationDataHelper::CONFIG_LARGE_IMAGE_HEIGHT, ScopeInterface::SCOPE_STORE, $storeId);
				break;
		}
				
		$keepFrame = 1 == $this->_scopeConfig->getValue(ConfigurationDataHelper::CONFIG_IMAGE_FORMAT, ScopeInterface::SCOPE_STORE, $storeId);
		$image = $this->_imageHelper->init($product, 'product_page_image_medium');		
		$url = $image->keepFrame($keepFrame)->resize($width, $height)->getUrl();
		return $this->_redirectFactory->create()->setUrl($url);
	}
}