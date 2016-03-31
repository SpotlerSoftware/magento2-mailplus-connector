<?php
namespace MailPlus\MailPlus\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;
use MailPlus\MailPlus\Api\SubscriberRepositoryInterface;

class SubscriberRepository implements SubscriberRepositoryInterface
{

    protected $_subscriberCollectionFactory;
    protected $_searchResultsFactory;
    
    public function __construct(CollectionFactory $subscriberCollectionFactory,
                                SearchResultsFactory $searchResultsFactory)
    {
        $this->_subscriberCollectionFactory = $subscriberCollectionFactory;
        $this->_searchResultsFactory = $searchResultsFactory;
    }

    /**
     * {@inheritdoc}
     */

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection $collection */
        $collection = $this->_subscriberCollectionFactory->create();
        $collection
            ->addSubscriberTypeField()
            ->showStoreInfo()
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
}