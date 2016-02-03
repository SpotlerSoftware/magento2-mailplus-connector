<?php
namespace MailPlus\MailPlus\Api\Data;

interface SubscriberSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface {
	
	/**
	 * @api
	 * @return \MailPlus\MailPlus\Api\Data\SubscriberInterface[]
	 */
	public function getItems();
	
	/**
	 * @api
	 * param \MailPlus\MailPlus\Api\Data\SubscriberInterface[]
	 * @return $this
	 */
	public function setItems(array $items);
	
}