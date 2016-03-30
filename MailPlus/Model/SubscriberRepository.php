<?php
namespace MailPlus\MailPlus\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Newsletter\Model\QueueFactory;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;
use MailPlus\MailPlus\Api\SubscriberRepositoryInterface;

class SubscriberRepository implements SubscriberRepositoryInterface
{
	
	protected $_subscriberCollectionFactory;
	
	protected $_searchResultsFactory;
	protected  $_queueFactory;

	public function __construct(CollectionFactory $subscriberCollectionFactory,
								SearchResultsFactory $searchResultsFactory,
								QueueFactory $queueFactory)
	{
		$this->_subscriberCollectionFactory = $subscriberCollectionFactory;	
		$this->_searchResultsFactory = $searchResultsFactory;
		$this->_queueFactory = $queueFactory;
	}
	
	/**
	 * {@inheritdoc}
	 */

	public function getList(SearchCriteriaInterface $searchCriteria)
	{
		$collection = $this->_subscriberCollectionFactory->create();
		$collection
			->addSubscriberTypeField()
			->showStoreInfo()
			->setPageSize($searchCriteria->getPageSize())
			->setCurPage($searchCriteria->getCurrentPage())
			->load();
		
		$searchResult = $this->_searchResultsFactory->create();
		$searchResult->setSearchCriteria($searchCriteria);
		$searchResult->setItems($collection->getItems());
		$searchResult->setTotalCount($collection->getSize());
		
		return $searchResult;
	}
}