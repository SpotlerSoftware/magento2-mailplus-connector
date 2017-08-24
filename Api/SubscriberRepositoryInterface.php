<?php

namespace MailPlus\MailPlus\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Newsletter\Model\Subscriber;

interface SubscriberRepositoryInterface
{

    /**
     * Get subscriber list
     * @param SearchCriteriaInterface $searchCriteria
     * @return \MailPlus\MailPlus\Api\Data\SubscriberSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Save a subscriber
     *
     * @param \MailPlus\MailPlus\Api\Data\SubscriberInterface $subscriber
     * @return \MailPlus\MailPlus\Api\Data\SubscriberInterface
     */
    public function save(\MailPlus\MailPlus\Api\Data\SubscriberInterface $subscriber);
}