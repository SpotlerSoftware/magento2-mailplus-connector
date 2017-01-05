<?php
namespace MailPlus\MailPlus\Observer;

use Magento\Customer\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote;
use MailPlus\MailPlus\Model\QuoteConversion;
use MailPlus\MailPlus\Model\QuoteConversionFactory;

class CreateCartObserver implements ObserverInterface
{
    private $quoteConversionFactory;
    private $sessionManager;

    /**
     * CreateCartObserver constructor.
     * @param Session $customerSession
     * @param QuoteConversionFactory $quoteConversionFactory
     * @internal param PhpCookieManager $cookieManager
     */
    public function __construct(Session $customerSession, QuoteConversionFactory $quoteConversionFactory)
    {
        $this->quoteConversionFactory = $quoteConversionFactory;
        $this->sessionManager = $customerSession;
    }

    public function execute(Observer $observer)
    {
        $mpId = $this->sessionManager->getMpId();
        if(!isset($mpId)){
            return;
        }
        //Only use mpId for one cart creation
        $this->sessionManager->setMpId(null);
        /** @var Quote $quote */
        $quote = $observer->getData('quote');

        /** @var QuoteConversion $quoteConversion */
        $quoteConversion = $this->quoteConversionFactory->create();
        $quoteConversion->load($quote->getId());
        if (empty($quoteConversion->getMailPlusId())) {
            $quoteConversion->setMailPlusId($mpId);
            $quoteConversion->setQuoteId($quote->getId());
            $quoteConversion->setIsObjectNew(true);
        }
        $quoteConversion->save();
    }
}