<?php
namespace MailPlus\MailPlus\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $quoteConversionTable = $setup->getConnection()
                ->newTable('mp_quote_conversion')
                ->addColumn(
                    'mailplus_id',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Mailplus ID'
                )
                ->addColumn(
                    'quote_id',
                    Table::TYPE_INTEGER,
                    10,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Quote ID'
                )
                ->addIndex(
                    $setup->getIdxName('mp_quote_conversion', ['quote_id']),
                    ['quote_id']
                )
                ->addForeignKey('conversion_quote',
                    'quote_id',
                    'quote',
                    'entity_id');
            $setup->getConnection()->createTable($quoteConversionTable);
        }
        if (version_compare($context->getVersion(), '1.2.0') < 0) {
            $catalogRuleUpdatedAtTable = $setup->getConnection()
                ->newTable('mp_catalog_rule_updated_at')
                ->addColumn(
                    'catalog_rule_id',
                    Table::TYPE_INTEGER,
                    10,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Rule ID'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'primary' => false,
                        'default' => Table::TIMESTAMP_INIT_UPDATE
                    ],
                    'Rule updated at'
                )
                ->addIndex(
                    $setup->getIdxName('mp_catalog_rule_updated_at', ['catalog_rule_id']),
                    ['catalog_rule_id']
                )
                ->addForeignKey('catalogrule',
                    'catalog_rule_id',
                    'catalogrule',
                    'rule_id');
            $setup->getConnection()->createTable($catalogRuleUpdatedAtTable);
        }
        if (version_compare($context->getVersion(), '1.4.1') < 0) {
            $setup->getConnection()
                ->dropForeignKey('mp_quote_conversion', 'conversion_quote')
                ->addForeignKey('conversion_quote',
                    'mp_quote_conversion',
                    'quote_id',
                    'quote',
                    'entity_id',
                    \Magento\Framework\DB\Adapter\AdapterInterface::FK_ACTION_CASCADE);
        }
        $setup->endSetup();
    }
}