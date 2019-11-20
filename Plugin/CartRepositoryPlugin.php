<?php

namespace MailPlus\MailPlus\Plugin;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote\Item;

class CartRepositoryPlugin
{

    private $cartItemExtensionFactory;

    /**
     * CartItemRepositoryPlugin constructor.
     */
    public function __construct(\Magento\Quote\Api\Data\CartItemExtensionFactory $cartItemExtensionFactory)
    {
        $this->cartItemExtensionFactory = $cartItemExtensionFactory;
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param \Magento\Quote\Api\Data\CartSearchResultsInterface $result
     * @return \Magento\Quote\Api\Data\CartSearchResultsInterface
     */
    public function afterGetList(CartRepositoryInterface $subject, $result)
    {
        /** @var \Magento\Quote\Model\Quote $cart */
        foreach ($result->getItems() as $cart) {
            $items = $cart->getItems();

            if ($items) {
                /** @var Item $item */
                foreach ($items as $item) {
                    $extensionAttributes = $this->getCartItemExtensionAttributes($item);
                    $extensionAttributes->setMpProductId($item->getProduct()->getId());
                    $item->setExtensionAttributes($extensionAttributes);
                }
            }
        }
        return $result;
    }

    /**
     * @param Item $item
     * @return \Magento\Quote\Api\Data\CartItemExtension
     */
    private function getCartItemExtensionAttributes(Item $item)
    {
        $extensionAttributes = $item->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->cartItemExtensionFactory->create();
        }
        return $extensionAttributes;
    }
}
