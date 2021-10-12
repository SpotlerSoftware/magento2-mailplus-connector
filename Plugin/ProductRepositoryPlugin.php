<?php

namespace MailPlus\MailPlus\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;

class ProductRepositoryPlugin
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $_storeManager;
    /**
     * @var \Magento\Tax\Model\Config
     */
    private $_taxConfig;
    /**
     * @var \Magento\Catalog\Helper\Data
     */
    private $catalogHelper;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Tax\Model\Config $taxConfig,
        \Magento\Catalog\Helper\Data $catalogHelper
    ) {
        $this->_storeManager = $storeManager;
        $this->_taxConfig = $taxConfig;
        $this->catalogHelper = $catalogHelper;
    }

    /**
     * Process product price with Tax
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param float $price
     * @return float
     * @throws
     */
    public function getPriceWithTax($product, $price)
    {
        $includeTax = true;
        $store = $this->_storeManager->getStore();
        $_configDisplayTax = $this->_taxConfig->getPriceDisplayType($store);
        if ($_configDisplayTax == \Magento\Tax\Model\Config::DISPLAY_TYPE_EXCLUDING_TAX) {
            $includeTax = false;
        }

        return $this->catalogHelper->getTaxPrice($product, $price, $includeTax);
    }

    /**
     * @param ProductRepositoryInterface $subject
     * @param \Magento\Catalog\Api\Data\ProductSearchResultsInterface $result
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function afterGetList(ProductRepositoryInterface $subject, $result)
    {
        /** @var \Magento\Catalog\Model\Product $item */
        foreach ($result->getItems() as $item) {
            $finalPrice = $this->getPriceWithTax($item, $item->getFinalPrice());
            $extensionAttributes = $item->getExtensionAttributes();
            $extensionAttributes->setFinalPrice($finalPrice);
            $regularPrice = $this->getPriceWithTax($item, $item->getPriceInfo()->getPrice('regular_price')->getValue());
            $extensionAttributes->setRegularPrice($regularPrice);
            $item->setExtensionAttributes($extensionAttributes);
        }
        return $result;
    }
}
