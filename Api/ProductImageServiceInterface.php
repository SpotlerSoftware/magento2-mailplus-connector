<?php

namespace MailPlus\MailPlus\Api;

use Magento\Framework\Controller\ResultInterface;

interface ProductImageServiceInterface
{
    /**
     * Get productImage list
     * @param int $productId
     * @param int $storeId
     * @return ResultInterface
     */
    public function get(int $productId, int $storeId);
}