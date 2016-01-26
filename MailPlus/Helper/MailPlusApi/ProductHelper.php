<?php

namespace MailPlus\MailPlus\Helper\MailPlusApi;

use Magento\Framework\App\Helper\AbstractHelper;

class ProductHelper extends AbstractHelper {
	
	/**
	 * @var \Magento\Framework\UrlInterface
	 */
	protected $_urlBuilder;
	
	/** 
	 * @var \Magento\Review\Model\RatingFactory
	 */
	protected $_ratingFactory;
	
	/**
	 * @param \Magento\Framework\App\Helper\Context $context
	 * @param \Magento\Framework\UrlInterface $urlBuilder
	 * @param \Magento\Review\Model\RatingFactory $ratingFactory
	 */
	public function __construct(\Magento\Framework\App\Helper\Context $context,
			\Magento\Framework\UrlInterface $urlBuilder,
			\Magento\Review\Model\RatingFactory $ratingFactory) {
		parent::__construct($context);

		$this->_urlBuilder = $urlBuilder;
		$this->_ratingFactory = $ratingFactory;
	}
	
	/**
	 * \Magento\Catalog\Model\Product $product
	 */
	public function getExternalId($product) {
		return $product->getStoreId () . '-' . $product->getId ();
	}
	
	public function getReviewUrl($product) {
		return $this->_urlBuilder->getUrl('review/product/list', [
						'id' => $product->getId(),
						'category' => $product->getCategoryId()
		]);
		
	}
	
	public function getProductData($product) {
		
		$externalId = $this->getExternalId($product);
		$description = strip_tags($product->getShortDescription());
		if (empty($description)) {
			$description = $product->getName();
		}
		
		$imageUrl = $this->_urlBuilder->getUrl('mailplus/image/get/', array('id' => $externalId, 'f' => 'n'));
		$imageLargeUrl = $this->_urlBuilder->getUrl('mailplus/image/get/', array('id' => $externalId, 'f' => 'l'));
		
		$storeName = $product->getStore()->getWebsite()->getName() .
			' - ' . $product->getStore()->getGroup()->getName() .
			' - ' . $product->getStore()->getName();
		
		$visible = \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED == $product->getStatus() &&
			 \Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE != $product->getVisilibity();
		
		 /*
		  * TODO:
		  *  - specifications
		  *  - category 
		  */
		
		$categoryPaths = array(); // TODO
			 
		$data = array (
				'update' => TRUE,
				'product' => array(
						'externalId' => $externalId,
						'sku' => $product->getSku(),
						'gtin' => $product->getId(),
						'name' => $product->getName(),
						'description' => $description,
						'category' => $categoryPaths ? reset ( $categoryPaths ) : '',
						'price' => round ( $product->getFinalPrice () * 100 ), // must be in cents
						'oldPrice' => round ( $product->getPrice () * 100 ), // must be in cents
						'link' => $product->getProductUrl (),
						'brand' => $product->getManufacturer(),
						'imageUrl' => $imageUrl,
						'imageLargeUrl' => $imageLargeUrl,
						'addToCartLink' => '',
						'language' => $storeName,
						'visible' => ($visible ? "true" : "false"),
						'reviewLink' => $this->getReviewUrl($product)
				) 
		);
		
		$ratingSummary = $this->_ratingFactory->create()->getEntitySummary($product->getId());
		if ($ratingSummary->getSum() > 0) {
			$ratingPerc = $ratingSummary->getSum() / $ratingSummary->getCount();
			// Normalize to 0-5
			$data['product']['ratingValue'] = 5 * ($ratingPerc / 100); 
		}
		
		if ($data['product']['price'] == $data['product']['oldPrice']) {
			unset($data['product']['oldPrice']);
		}
		
		return $data;
	}
}
