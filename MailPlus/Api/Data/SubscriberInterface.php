<?php
namespace MailPlus\MailPlus\Api\Data;

interface SubscriberInterface {


	/**
	 * @api
	 * @param int $value
	 * @return $this
	 */
	public function setId($value);
	
	/**
	 * @api
	 * @return int
	 */
	public function getId();


	/**
	 * @api
	 * @param int $value
	 * @return $this
	 */
	public function setEmail($value);

	/**
	 * @api
	 * @return string
	 */
	public function getEmail();


	/**
	 * @api
	 * @param int $value
	 * @return $this
	 */
	public function setStoreId($value);


	/**
	 * @api
	 * @return int
	 */
	public function getStoreId();

	/**
	 * @api
	 * @param int $value
	 * @return $this
	 */
	public function setWebsiteId($value);

	/**
	 * @api
	 * @return int
	 */
	public function getWebsiteId();

	/**
	 * @api
	 * @param int $value
	 * @return $this
	 */
	public function setCustomerId($value);

	/**
	 * @api
	 * @return int
	 */
	public function getCustomerId();


	/**
	 * @api
	 * @param int $value
	 * @return $this
	 */
	public function setStatus($value);

	/**
	 * @api
	 * @return int
	 */
	public function getStatus();
	
	
}