<?php

namespace MailPlus\MailPlus\Api;

use MailPlus\MailPlus\Api\Data\QuoteConversionInterface;

interface QuoteConversionRepositoryInterface {

    /**
     * @param Data\QuoteConversionInterface $quoteConversion
     * @return mixed
     */
    public function save(QuoteConversionInterface $quoteConversion);
}