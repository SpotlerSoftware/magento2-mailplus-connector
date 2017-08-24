<?php
namespace MailPlus\MailPlus\Model;

use Magento\Framework\Model\AbstractModel;
use MailPlus\MailPlus\Api\Data\QuoteConversionInterface;

class QuoteConversion extends AbstractModel implements QuoteConversionInterface {

    const MAILPLUS_ID = "mailplus_id";

    const QUOTE_ID = "quote_id";

    public function _construct()
    {
        $this->_init('MailPlus\MailPlus\Model\ResourceModel\QuoteConversion');
    }

    public function setIsObjectNew($value){
        $this->_isObjectNew = $value;
    }

    public function getMailPlusId()
    {
        return $this->getData(self::MAILPLUS_ID);
    }

    public function setMailPlusId($mailPlusId)
    {
        $this->setData(self::MAILPLUS_ID, $mailPlusId);
    }

    public function setQuoteId($quoteId)
    {
        $this->setData(self::QUOTE_ID, $quoteId);
    }

    public function getQuoteId()
    {
        return $this->getData(self::QUOTE_ID);
    }
}