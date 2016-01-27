<?php

/**
 * 
 */
namespace MailPlus\MailPlus\Controller\Image;

class Get extends \Magento\Framework\App\Action\Action {
	
	const FORMAT_NORMAL = 'n';
	const FORMAT_LARGE = 'l';
	
	const SIZE_NORMAL = 145;
	const SIZE_LARGE = 185;
	
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
	 * @param \Magento\Framework\App\Action\Context $context
	 * @param \Magento\Catalog\Helper\Image $imageHelper
	 * @param \Magento\Framework\Controller\Result\ForwardFactory $forwardFactory
	 * @param \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
	 * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
	 */
	public function __construct(\Magento\Framework\App\Action\Context $context,
			\Magento\Catalog\Helper\Image $imageHelper,
			\Magento\Framework\Controller\Result\ForwardFactory $forwardFactory,
			\Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
			\Magento\Catalog\Api\ProductRepositoryInterface $productRepository) {
		parent::__construct($context);
		
		$this->_forwardFactory = $forwardFactory;
		$this->_redirectFactory = $redirectFactory;
        $this->_imageHelper = $imageHelper;
        $this->_productRepository = $productRepository;
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
		
		$size = self::SIZE_NORMAL;
		switch ($format) {
			case self::FORMAT_NORMAL :
				$size = self::SIZE_NORMAL;
				break;
			CASE self::FORMAT_LARGE :
				$size = self::SIZE_LARGE;
				break;
		}
		
		
		$image = $this->_imageHelper->init($product, 'product_page_image_medium');		
		$url = $image->keepFrame(true)->resize($size)->getUrl();
		return $this->_redirectFactory->create()->setUrl($url);
	}
}