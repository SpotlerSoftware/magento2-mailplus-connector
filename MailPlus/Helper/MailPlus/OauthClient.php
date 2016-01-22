<?php
/*
 * Copyright 2016 MailPlus
*
* Licensed under the Apache License, Version 2.0 (the "License"); you may not
* use this file except in compliance with the License. You may obtain a copy
* of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
* WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
* License for the specific language governing permissions and limitations
* under the License.
*/
namespace MailPlus\MailPlus\Helper\MailPlus;
/**
 * Fixes http://framework.zend.com/issues/browse/ZF-9840
 * Issue: Zend_Rest_Client::restPost forces Content-Type to be 'application/x-www-form-urlencoded'
 */
class OauthClient extends \Zend_Oauth_Client
{
	
	protected $_keepContentType = false;
	
	public function setKeepContentType( $keep=true )
	{
		$this->_keepContentType = (bool) $keep;
	}

	/**
	 * Clear all GET and POST parameters
	 *
	 * Should be used to reset the request parameters if the client is
	 * used for several concurrent requests.
	 *
	 * clearAll parameter controls if we clean just parameters or also
	 * headers and last_*
	 *
	 * @param bool $clearAll Should all data be cleared?
	 * @return Zend_Http_Client
	 */
	public function resetParameters($clearAll = false)
	{
		// Reset parameter data
		$this->paramsGet     = array();
		$this->paramsPost    = array();
		$this->files         = array();
		$this->raw_post_data = null;

		if($clearAll) {
			$this->headers = array();
			$this->last_request = null;
			$this->last_response = null;
		} else {
			// Clear outdated headers
			if (false === $this->_keepContentType && isset($this->headers[strtolower(self::CONTENT_TYPE)])) {
				unset($this->headers[strtolower(self::CONTENT_TYPE)]);
			}
			if (isset($this->headers[strtolower(self::CONTENT_LENGTH)])) {
				unset($this->headers[strtolower(self::CONTENT_LENGTH)]);
			}
		}

		return $this;
	}

}
