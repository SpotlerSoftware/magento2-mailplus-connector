<?php
namespace MailPlus\MailPlus\Model;

class Subscribers implements \MailPlus\MailPlus\Api\SubscribersInterface {
	
	protected $_subscriberCollectionFactory;
	
	protected $_searchResultsFactory;
	protected  $_queueFactory;
	
	public function __construct(\Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $subscriberCollectionFactory,
			\Magento\Framework\Api\SearchResultsFactory $searchResultsFactory,
			\Magento\Newsletter\Model\QueueFactory $queueFactory) {
		$this->_subscriberCollectionFactory = $subscriberCollectionFactory;	
		$this->_searchResultsFactory = $searchResultsFactory;
		$this->_queueFactory = $queueFactory;
	}
	
	/**
	 * {@inheritdoc}
	 */
	
	public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria) {
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