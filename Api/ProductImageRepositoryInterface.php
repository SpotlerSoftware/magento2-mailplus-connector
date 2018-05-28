<?php

namespace MailPlus\MailPlus\Api;


interface ProductImageRepositoryInterface
{
    /**
     * Get productImage listMailPlus\MailPlus\Service\HealthCheckRepositoryMailPlus\MailPlus\Service\HealthCheckRepositoryMailPlus\MailPlus\Service\HealthCheckRepository
     *
     * Should never return as it does a redirect to the image
     *
     * @param int $productId
     * @param int $storeId
     * @return void
     */
    public function get(int $productId, int $storeId);
}