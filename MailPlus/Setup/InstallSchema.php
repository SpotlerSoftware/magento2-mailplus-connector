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
namespace MailPlus\MailPlus\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface {
	/**
	 *
	 * {@inheritdoc} @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
		$installer = $setup;
		$installer->startSetup ();
		
		$table = $installer->getConnection ()->newTable ( $installer->getTable ( 'mailplus_bounce' ) )->addColumn ( 'id', Table::TYPE_INTEGER, null, [ 
				'identity' => true,
				'unsigned' > true,
				'nullable' => false,
				'primary' => true 
		], 'Id' )->addColumn ( 'mailplus_id', Table::TYPE_TEXT, 255, [ 
				'nullable' => false 
		], 'MailPlus Id' )->addColumn ( 'firstname', Table::TYPE_TEXT, 255, [ 
				'nullable' => false 
		], 'First name' )->addColumn ( 'lastname', Table::TYPE_TEXT, 255, [ 
				'nullable' => false 
		], 'Last name' )->addColumn ( 'insertion', Table::TYPE_TEXT, 255, [ 
				'nullable' => false 
		], 'Insertion' )->addColumn ( 'email', Table::TYPE_TEXT, 255, [ 
				'nullable' => false 
		], 'Email' )->addColumn ( 'total_received', Table::TYPE_INTEGER, null, [ 
				'nullable' => false,
				'unsigned' => true 
		], 'Total recieved' )->addColumn ( 'is_test', Table::TYPE_SMALLINT, null, [ 
				'nullable' => false 
		], 'Is test' )->addColumn ( 'is_customer_alerted', Table::TYPE_INTEGER, null, [ 
				'nullable' => false,
				'default' => '0' 
		], 'Is customer alerted' )->addColumn ( 'last_bounce_date', Table::TYPE_DATETIME, null, [ 
				'nullable' => false 
		], 'Last bounce date' )->addIndex ( $installer->getIdxName ( 'mailplus_bounce', [ 
				'mailplus_id' 
		] ), [ 
				'mailplus_id' 
		] );
		
		$installer->getConnection ()->createTable ( $table );
		
		$table = $installer->getConnection ()->newTable ( $installer->getTable ( 'mailplus_product' ) )->addColumn ( 'entity_id', Table::TYPE_INTEGER, null, [ 
				'identity' => true,
				'unsigned' > true,
				'nullable' => false,
				'primary' => true 
		], 'Id' )->addColumn ( 'catalog_product_entity_id', Table::TYPE_INTEGER, null, [ 
				'nullable' => false,
				'unsigned' => true 
		], 'Product id' )->addColumn ( 'store_id', Table::TYPE_INTEGER, null, [ 
				'nullable' => false,
				'unsigned' => true 
		], 'Store id' )->addColumn ( 'price', Table::TYPE_DECIMAL, [12,4], [ 
				'nullable' => false 
		], 'Price' )->addColumn ( 'checksum', Table::TYPE_BIGINT, 20, [ 
				'nullable' => false 
		], 'Checksum' )->addColumn ( 'created_at', Table::TYPE_DATETIME, null, [ 
				'nullable' => false 
		], 'Created at' )->addColumn ( 'updated_at', Table::TYPE_DATETIME, null, [ 
				'nullable' => false 
		], 'Updated at' )->addIndex ( $installer->getIdxName ( 'mailplus_product', [ 
				'store_id' 
		] ), [ 
				'store_id' 
		] )->addIndex ( $installer->getIdxName ( 'mailplus_product', [ 
				'catalog_product_entity_id' 
		] ), [ 
				'catalog_product_entity_id' 
		] );
		
		$installer->getConnection ()->createTable ( $table );
		
		$table = $installer->getConnection ()->newTable (
				$installer->getTable ( 'mailplus_restqueue' )
		)->addColumn ( 'restqueue_id', Table::TYPE_INTEGER, null,
				['identity' => true, 'unsigned' > true, 'nullable' => false, 'primary' => true],
				'Id'
		)->addColumn ( 'method', Table::TYPE_TEXT, 255,
				['nullable' => false],
				'Method'
		)->addColumn ( 'url', Table::TYPE_TEXT, 255,
				['nullable' => false],
				'URL'
		)->addColumn ( 'method', Table::TYPE_TEXT, 255,
				['nullable' => false],
				'Product id'
		)->addColumn ( 'payload', Table::TYPE_BLOB, null,
				['nullable' => true],
				'Payload'
		)->addColumn ( 'tries', Table::TYPE_INTEGER, null,
				['nullable' => false, 'unsigned' => true, 'default' => '0'],
				'Number of tries'
		)->addColumn ( 'last_error', Table::TYPE_TEXT, 2048,
				['nullable' => true],
				'Last error'
		)->addColumn ( 'last_response', Table::TYPE_TEXT, 2048,
				['nullable' => true],
				'Last response'
		)->addColumn ( 'created_at', Table::TYPE_DATETIME, null,
				['nullable' => false],
				'Created at'
		)->addColumn ( 'last_run_at', Table::TYPE_DATETIME, null,
				['nullable' => false],
				'Last run at'
		)->addColumn ( 'next_run_at', Table::TYPE_DATETIME, null,
				['nullable' => false],
				'Next run at'
		)->addColumn ( 'site', Table::TYPE_INTEGER, null,
				['nullable' => false],
				'Site ID'
		);
		$installer->getConnection ()->createTable ( $table );
		
		$table = $installer->getConnection ()->newTable (
				$installer->getTable ( 'mailplus_syncqueue' )
		)->addColumn ( 'syncqueue_id', Table::TYPE_INTEGER, null,
				['identity' => true, 'unsigned' > true, 'nullable' => false, 'primary' => true],
				'Id'
		)->addColumn ( 'synctype', Table::TYPE_TEXT, 10,
				['nullable' => false],
				'Sync item type'
		)->addColumn ( 'websiteid', Table::TYPE_INTEGER, null,
				['nullable' => false],
				'Site ID'
		)->addColumn ( 'syncid', Table::TYPE_INTEGER, null,
				['nullable' => false],
				'Object id'
		)->addColumn ( 'created_at', Table::TYPE_DATETIME, null,
				['nullable' => false],
				'Created at'
		)->addIndex ( $installer->getIdxName ( 'mailplus_syncqueue', ['synctype', 'created_at']),
				['synctype', 'created_at']
		)->addIndex ( $installer->getIdxName ( 'mailplus_syncqueue', ['websiteid']),
				['websiteid']
		);
		$installer->getConnection ()->createTable ( $table );
		
		echo "test";
		
		$table = $installer->getConnection ()->newTable (
				$installer->getTable ( 'mailplus_user' )
		)->addColumn ( 'user_id', Table::TYPE_INTEGER, null,
				['identity' => true, 'unsigned' > true, 'nullable' => false, 'primary' => true],
				'Id'
		)->addColumn ( 'customer_id', Table::TYPE_INTEGER, null,
				['nullable' => false, 'unsigned' => true],
				'Customer id'
		)->addColumn ( 'mailplus_id', Table::TYPE_TEXT, 50,
				['nullable' => true, 'default' => null],
				'MailPlus id'
		)->addColumn ( 'store_id', Table::TYPE_SMALLINT, null,
				['nullable' => false, 'unsigned' => true],
				'MailPlus id'
		)->addColumn ( 'enabled', Table::TYPE_SMALLINT, null,
				['nullable' => false, 'default' => '0'],
				'enabled'
		)->addColumn ( 'is_test', Table::TYPE_SMALLINT, null,
				['nullable' => false, 'default' => '0'],
				'Is test'
		)->addColumn ( 'firstname', Table::TYPE_TEXT, 255,
				['nullable' => false],
				'First name'
		)->addColumn ( 'lastname', Table::TYPE_TEXT, 255,
				['nullable' => false],
				'Last name'
		)->addColumn ( 'email', Table::TYPE_TEXT, 255,
				['nullable' => true, 'default' => null],
				'Email'
		)->addColumn ( 'createts', Table::TYPE_INTEGER, null,
				['nullable' => false, 'unsigned' => true],
				'Created at timestamp'
		)->addIndex ( $installer->getIdxName ( 'mailplus_user', ['mailplus_id']),
				['mailplus_id']
		)->addIndex ( $installer->getIdxName ( 'mailplus_user', ['store_id']),
				['store_id']
		)->addIndex ( $installer->getIdxName ( 'mailplus_user', ['customer_id']),
				['customer_id']
		)->addForeignKey(
				$installer->getFkName('mailplus_user', 'customer_id', 'customer_entity', 'entity_id'),
				'customer_id',
				$installer->getTable('customer_entity'),
				'entity_id',
				Table::ACTION_CASCADE
		);
		
		$installer->getConnection ()->createTable ( $table );
		
		echo "test2";
		
		$table = $installer->getConnection ()->newTable (
				$installer->getTable ( 'mailplus_abandoned_campaign' )
		)->addColumn ( 'quote_id', Table::TYPE_INTEGER, null,
				['identity' => true, 'unsigned' > true, 'nullable' => false, 'primary' => true],
				'Id'
		)->addColumn ( 'created_at', Table::TYPE_DATETIME, null,
				['nullable' => false],
				'Created at'
		);
		$installer->getConnection ()->createTable ( $table );
		
		$table = $installer->getConnection ()->newTable (
				$installer->getTable ( 'mailplus_info' )
		)->addColumn ( 'id', Table::TYPE_INTEGER, null,
				['identity' => true, 'unsigned' > true, 'nullable' => false, 'primary' => true],
				'Id'
		)->addColumn ( 'name', Table::TYPE_TEXT, 255,
				['nullable' => false],
				'Name'
		)->addColumn ( 'value', Table::TYPE_TEXT, 255,
				['nullable' => false],
				'Value'
		)->addIndex ( $installer->getIdxName ( 'mailplus_info', ['name']),
				['name']
		);
		$installer->getConnection ()->createTable ( $table );
		
		$installer->endSetup ();
	}
}
