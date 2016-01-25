<?php

namespace MailPlus\MailPlus\Helper\MailPlusApi;

use Magento\Framework\App\Helper\AbstractHelper;

class ProductHelper extends AbstractHelper {
	
	/**
	 * 
	 * @var \Magento\Framework\UrlInterface $_urlBuilder
	 */
	protected $_urlBuilder;
	
	public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Framework\UrlInterface $urlBuilder) {
		parent::__construct($context);
		$this->_urlBuilder = $urlBuilder;
		//$this->_urlBuilder->setUseSession(false);
	}
	
	/**
	 * \Magento\Catalog\Model\Product $product
	 */
	public function getExternalId($product) {
		return $product->getStoreId () . '-' . $product->getId ();
	}
	
	public function getProductData($product) {
		
		$externalId = $this->getExternalId($product);
		$description = strip_tags($product->getShortDescription());
		if (empty($description)) {
			$description = $product->getName();
		}
		
		$categoryPaths = array(); // TODO
		
		$imageUrl = $this->_urlBuilder->getUrl('mailplus/image/get/', array('id' => $externalId, 'f' => 'n'));
		$imageLargeUrl = $this->_urlBuilder->getUrl('mailplus/image/get/', array('id' => $externalId, 'f' => 'l'));
		
		$storeName = $product->getStore()->getWebsite()->getName() .
			' - ' . $product->getStore()->getGroup()->getName() .
			' - ' . $product->getStore()->getName();
		
		$visible = \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED == $product->getStatus() &&
			 \Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE != $product->getVisilibity();
		
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
						'visible' => ($visible ? "true" : "false") 
				) 
		);
		
		if ($data['product']['price'] == $data['product']['oldPrice']) {
			unset($data['product']['oldPrice']);
		}
		
		return $data;
	}
}
