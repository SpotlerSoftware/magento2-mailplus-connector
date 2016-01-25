<?php
/*
 * Copyright 2014 MailPlus
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
namespace MailPlus\MailPlus\Helper\MailPlusApi;

/**
 * MailPlus customization:
 * - Use the Zend_Oauth_Client in getHttpClient
 */
class TokenAccess extends \Zend_Oauth_Token_Access
{
	/**
	 * Get OAuth client
	 *
	 * Use the Zend_Oauth_Client in getHttpClient
	 *
	 * @param  array $oauthOptions
	 * @param  null|string $uri
	 * @param  null|array|Zend_Config $config
	 * @param  bool $excludeCustomParamsFromHeader
	 * @return Mailplus_Oauth_Client
	 */
	public function getHttpClient(array $oauthOptions, $uri = NULL, $config = NULL, $excludeCustomParamsFromHeader = TRUE)
	{
		$client = new OauthClient($oauthOptions, $uri, $config, $excludeCustomParamsFromHeader);
		$client->setToken($this);
		return $client;
	}
}