<?php

namespace MailPlus\MailPlus\Api;

interface SubscribersInterface {
	
	/**
	 * Get subscriber list
	 * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
	 * @return \MailPlus\MailPlus\Api\Data\SubscriberSearchResultsInterface
	 */
	public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}