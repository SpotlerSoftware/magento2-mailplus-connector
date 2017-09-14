<?php

namespace MailPlus\MailPlus\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface CatalogRuleSearchResultsInterface extends SearchResultsInterface
{

    /**
     * @api
     * @return \Magento\CatalogRule\Api\Data\RuleInterface[]
     */
    public function getItems();

    /**
     * @api
     * @param $items \Magento\CatalogRule\Api\Data\RuleInterface[]
     * @return $this
     */
    public function setItems(array $items);

}