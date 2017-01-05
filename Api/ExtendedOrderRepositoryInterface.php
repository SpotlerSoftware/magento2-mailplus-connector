<?php

namespace MailPlus\MailPlus\Api;


//TODO: Remove this class when issue: https://github.com/magento/magento2/issues/8035 is resolved.

//This class is needed because magento doesn't load joined extension attributes for orders.

use Magento\Framework\Api\SearchCriteria;

interface ExtendedOrderRepositoryInterface
{

    /**
     * Get subscriber list
     * @param SearchCriteria $searchCriteria
     * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
     */
    public function getList(SearchCriteria $searchCriteria);
}