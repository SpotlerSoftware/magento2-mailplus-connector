<?php

namespace MailPlus\MailPlus\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CatalogRuleRepositoryInterface
{

    /**
     * Get catalog rule list
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \MailPlus\MailPlus\Api\Data\CatalogRuleSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}