<?php
namespace MailPlus\MailPlus\Observer;

use Magento\Customer\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Stdlib\Cookie\PhpCookieManager;
use Magento\Quote\Model\Quote;
use MailPlus\MailPlus\Model\QuoteConversion;
use MailPlus\MailPlus\Model\QuoteConversionFactory;

class CreateCartObserver implements ObserverInterface
{
    private $quoteConversionFactory;
    private $cookieManager;

    /**
     * CreateCartObserver constructor.
     * @param Session $cookieManager
     * @param QuoteConversionFactory $quoteConversionFactory
     * @internal param PhpCookieManager $cookieManager
     */
    public function __construct(PhpCookieManager $cookieManager, QuoteConversionFactory $quoteConversionFactory)
    {
        $this->quoteConversionFactory = $quoteConversionFactory;
        $this->cookieManager = $cookieManager;
    }

    public function execute(Observer $observer)
    {
        $mpId = $this->cookieManager->getCookie("mpId");
        if(!isset($mpId)){
            return;
        }
        //Only use mpId for one cart creation
        $this->cookieManager->deleteCookie("mpId");
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