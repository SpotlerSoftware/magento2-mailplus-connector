<?php
namespace MailPlus\MailPlus\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface SubscriberSearchResultsInterface extends SearchResultsInterface
{
	
	/**
	 * @api
	 * @return \MailPlus\MailPlus\Api\Data\SubscriberInterface[]
	 */
	public function getItems();

	/**
	 * @api
	 * @param $items \MailPlus\MailPlus\Api\Data\SubscriberInterface[]
	 * @return $this
	 */
	public function setItems(array $items);
	
}