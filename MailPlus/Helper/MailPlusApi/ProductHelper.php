<?php

namespace MailPlus\MailPlus\Helper\MailPlusApi;

use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Review\Model\RatingFactory;

class ProductHelper extends AbstractHelper {
		
	/** 
	 * @var RatingFactory
	 */
	protected $_ratingFactory;
	
	/**
	 * @var RatingFactory
	 */
	protected $_categoryFactory;

	/**
	 * @param Context $context
	 * @param RatingFactory $ratingFactory
	 * @param Collection $categoryCollection
	 */
	public function __construct(Context $context,
								RatingFactory $ratingFactory,
								Collection $categoryCollection) {
		parent::__construct($context);
		
		$this->_ratingFactory = $ratingFactory;
		$this->_categoryCollection = $categoryCollection;
	}
	
	/**
	 * \Magento\Catalog\Model\Product $product
	 */
	public function getExternalId($product) {
		return $product->getStoreId () . '-' . $product->getId ();
	}
	
	public function getReviewUrl($product) {
		return $product->getStore()->getUrl('review/product/list', [
						'id' => $product->getId(),
						'category' => $product->getCategoryId()
		]);
		
	}
	
	public function getProductCategoryPath($product) {
		$collection = $this->_categoryCollection
			->addIdFilter($product->getCategoryIds())
			->addAttributeToSelect('*')
			->load();
		
		$category = '';
		$catCount = 0;
		$catWithLongestPath = null;
		if ($collection->getSize() > 0) {
			foreach($collection as $category) {
				$currentCount = count(explode("/", $category->getPath()));
				if ($currentCount > $catCount) {
					$catWithLongestPath = $category;
					$catCount = $currentCount;
				}
			}
				
			if ($catWithLongestPath != null) {
				$parentCats = $category->getParentCategories($category);
				$pathNames = Array();
				foreach($parentCats as $category) {
					$pathNames[] = $category->getName();
				}
				$category = implode("/", $pathNames);
			}
		}
		
		return $category;
	}
	
	
	public function getProductData($product) {
		$externalId = $this->getExternalId($product);
		$description = strip_tags($product->getDescription());
		if (empty($description)) {
			$description = $product->getName();
		}
		
		$imageUrl = $product->getStore()->getUrl('mailplus/image/get/', array('id' => $externalId, 'f' => 'n'));
		$imageLargeUrl = $product->getStore()->getUrl('mailplus/image/get/', array('id' => $externalId, 'f' => 'l'));
		
		$storeName = $product->getStore()->getWebsite()->getName() .
			' - ' . $product->getStore()->getGroup()->getName() .
			' - ' . $product->getStore()->getName();
		
		$visible = Status::STATUS_ENABLED == $product->getStatus() &&
			 Visibility::VISIBILITY_NOT_VISIBLE != $product->getVisilibity();
		
		 /*
		  * TODO:
		  *  - specifications
		  */
			 
		$data = array (
				'update' => TRUE,
				'product' => array(
						'externalId' => $externalId,
						'sku' => $product->getSku(),
						'gtin' => $product->getId(),
						'name' => $product->getName(),
						'description' => $description,
						'category' => $this->getProductCategoryPath($product),
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
