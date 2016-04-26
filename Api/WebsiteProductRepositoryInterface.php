<?php
/**
 * Created by IntelliJ IDEA.
 * User: bob
 * Date: 3/30/16
 * Time: 3:52 PM
 */

namespace MailPlus\MailPlus\Api;


use Magento\Framework\Api\SearchCriteriaInterface;

interface WebsiteProductRepositoryInterface
{
    /**
     * Get product list
     *
     * @param int $storeId The store ID.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function getStoreProductList($storeId, SearchCriteriaInterface $searchCriteria);
}