<?php

namespace MailPlus\MailPlus\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;

class ProductRepositoryPlugin
{


    /**
     * @param ProductRepositoryInterface $subject
     * @param \Magento\Catalog\Api\Data\ProductSearchResultsInterface $result
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function afterGetList(ProductRepositoryInterface $subject, $result)
    {
        /** @var \Magento\Catalog\Model\Product $item */
        foreach ($result->getItems() as $item) {
            $finalPrice = $item->getFinalPrice();
            $extensionAttributes = $item->getExtensionAttributes();
            $extensionAttributes->setFinalPrice($finalPrice);
            $regularPrice = $item->getPriceInfo()->getPrice('regular_price')->getValue();
            $extensionAttributes->setRegularPrice($regularPrice);
            $item->setExtensionAttributes($extensionAttributes);
        }
        return $result;
    }
}