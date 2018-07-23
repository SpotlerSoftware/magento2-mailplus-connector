<?php

namespace MailPlus\MailPlus\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CatalogRuleUpdatedAt extends AbstractDb
{
    public function _construct()
    {
        $this->_useIsObjectNew = true;
        $this->_isPkAutoIncrement = false;
        $this->_init($this->getTable('mp_catalog_rule_updated_at'), 'catalog_rule_id');
    }
}