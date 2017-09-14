<?php

namespace MailPlus\MailPlus\Model;

use Magento\CatalogRule\Model\ResourceModel\Rule\Collection;
use Magento\CatalogRule\Model\ResourceModel\Rule\CollectionFactory;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsFactory;
use MailPlus\MailPlus\Api\CatalogRuleRepositoryInterface;

class CatalogRuleRepository implements CatalogRuleRepositoryInterface
{

    protected $_catalogRuleCollectionFactory;
    protected $_searchResultsFactory;

    public function __construct(CollectionFactory $catalogRuleCollectionFactory,
                                SearchResultsFactory $searchResultsFactory)
    {
        $this->_catalogRuleCollectionFactory = $catalogRuleCollectionFactory;
        $this->_searchResultsFactory = $searchResultsFactory;
    }

    /**
     * {@inheritdoc}
     */

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->_catalogRuleCollectionFactory->create();

        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }

        $collection
            ->setPageSize($searchCriteria->getPageSize())
            ->setCurPage($searchCriteria->getCurrentPage())
            ->load();

        /** @var \Magento\Framework\Api\SearchResults $searchResult */
        $searchResult = $this->_searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup $filterGroup
     * @param Collection $collection
     * @return void
     */
    protected function addFilterGroupToCollection(
        FilterGroup $filterGroup,
        Collection $collection
    )
    {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $fields[] = $filter->getField();
            $conditionType = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $conditionValue = $conditionType != 'in' && $conditionType != 'nin' ? $filter->getValue() : explode(',', $filter->getValue());
            $conditions[] = [$conditionType => $conditionValue];
        }

        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
    }
}
