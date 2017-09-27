<?php

namespace MailPlus\MailPlus\Model;

use DateTime;
use Magento\CatalogRule\Model\ResourceModel\Rule\Collection;
use Magento\CatalogRule\Model\ResourceModel\Rule\CollectionFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsFactory;
use MailPlus\MailPlus\Api\CatalogRuleRepositoryInterface;
use MailPlus\MailPlus\Model\CatalogRuleUpdatedAtFactory;

class CatalogRuleRepository implements CatalogRuleRepositoryInterface
{

    protected $_catalogRuleCollectionFactory;
    protected $_searchResultsFactory;
    private $_extensionAttributesFactory;
    private $_catalogRuleUpdatedAtFactory;

    /**
     * CatalogRuleRepository constructor.
     * @param CollectionFactory $catalogRuleCollectionFactory
     * @param SearchResultsFactory $searchResultsFactory
     * @param ExtensionAttributesFactory $extensionAttributesFactory
     * @param \MailPlus\MailPlus\Model\CatalogRuleUpdatedAtFactory $catalogRuleUpdatedAtFactory
     */
    public function __construct(CollectionFactory $catalogRuleCollectionFactory,
                                SearchResultsFactory $searchResultsFactory,
                                ExtensionAttributesFactory $extensionAttributesFactory,
                                CatalogRuleUpdatedAtFactory $catalogRuleUpdatedAtFactory)
    {
        $this->_catalogRuleCollectionFactory = $catalogRuleCollectionFactory;
        $this->_searchResultsFactory = $searchResultsFactory;
        $this->_extensionAttributesFactory = $extensionAttributesFactory;
        $this->_catalogRuleUpdatedAtFactory = $catalogRuleUpdatedAtFactory;
    }

    /**
     * {@inheritdoc}
     */

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        // Filter out updated_at filters
        $updatedAtFilter = null;
        $filterGroups = $searchCriteria->getFilterGroups();
        foreach ($filterGroups as $filterGroup) {
            $filters = $filterGroup->getFilters();
            foreach ($filters as $filterIndex => $filter) {
                if ($filter->getField() == 'updated_at') {
                    unset($filters[$filterIndex]);
                    $updatedAtFilter = $filter;
                    $filterGroup->setFilters(array_values($filters));
                }
            }
        }

        /** @var Collection $collection */
        $collection = $this->_catalogRuleCollectionFactory->create();

        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }

        $collection
            ->setPageSize($searchCriteria->getPageSize())
            ->setCurPage($searchCriteria->getCurrentPage())
            ->load();

        $items = $collection->getItems();
        /** @var \Magento\CatalogRule\Model\Rule $item */
        foreach ($items as $itemIndex => $item) {
            $extensionAttributes = $item->getExtensionAttributes();
            if (!$extensionAttributes) {
                $extensionAttributes = $this->_extensionAttributesFactory->create('Magento\CatalogRule\Api\Data\RuleInterface');
            }

            // Get the updatedAt for the rule or set it to current datetime.
            $ruleUpdatedAt = $this->_catalogRuleUpdatedAtFactory->create();
            $ruleUpdatedAt->load($item->getRuleId());
            $dateTime = null;
            if (empty($ruleUpdatedAt->getUpdatedAt())) {
                $dateTime = (new DateTime())->format('c');
                $ruleUpdatedAt->setUpdatedAt($dateTime);
                $ruleUpdatedAt->setCatalogRuleId($item->getRuleId());
                $ruleUpdatedAt->setIsObjectNew(true);
                $ruleUpdatedAt->save();
            } else {
                $dateTime = $ruleUpdatedAt->getUpdatedAt();
            }

            $extensionAttributes->setUpdatedAt(date("c", strtotime($dateTime)));
            $item->setExtensionAttributes($extensionAttributes);

            //Filter unchanged
            if ($updatedAtFilter) {
                if ($updatedAtFilter->getConditionType() != 'gt') {
                    throw new \UnexpectedValueException('Only condition type "gt" supported for updated_at');
                }

                $filterDateTime = DateTime::createFromFormat('Y-m-d\+H:i:s', $updatedAtFilter->getValue());
                if (strtotime($dateTime) < $filterDateTime->getTimestamp()) {
                    unset($items[$itemIndex]);
                }
            }
        }


        /** @var \Magento\Framework\Api\SearchResults $searchResult */
        $searchResult = $this->_searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);

        $searchResult->setItems($items);
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
