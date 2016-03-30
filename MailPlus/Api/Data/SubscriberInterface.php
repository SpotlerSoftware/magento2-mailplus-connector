<?php
namespace MailPlus\MailPlus\Api\Data;

interface SubscriberInterface {
	
	/**
	 * @api
	 * @return int
	 */
	public function getId();
	
	/**
	 * @api
	 * @return string
	 */
	public function getEmail();

	
	/**
	 * @api
	 * @return int
	 */
	public function getStoreId();
	
	/**
	 * @api
	 * @return int
	 */
	public function getWebsiteId();
	
	/**
	 * @api
	 * @return int
	 */
	public function getCustomerId();
	
	/**
	 * @api
	 * @return int
	 */
	public function getStatus();
	
	
}