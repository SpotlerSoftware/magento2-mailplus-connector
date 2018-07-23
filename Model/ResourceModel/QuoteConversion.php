<?php
namespace MailPlus\MailPlus\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class QuoteConversion extends AbstractDb
{
    public function _construct()
    {
        $this->_useIsObjectNew = true;
        $this->_isPkAutoIncrement = false;
        $this->_init($this->getTable('mp_quote_conversion'), 'quote_id');
    }
}