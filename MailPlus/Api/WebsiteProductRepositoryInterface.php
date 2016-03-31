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
     * @return \MailPlus\MailPlus\Api\Data\WebsiteProductSearchResultInterface
     */
    public function getList($storeId, SearchCriteriaInterface $searchCriteria);
}