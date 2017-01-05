<?php
namespace MailPlus\MailPlus\Api\Data;


interface QuoteConversionInterface
{
    public function setMailPlusId($mailPlusId);

    public function getMailPlusId();

    public function setQuoteId($quoteId);

    public function getQuoteId();
}