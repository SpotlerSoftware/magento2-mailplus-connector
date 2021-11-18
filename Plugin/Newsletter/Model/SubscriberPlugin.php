<?php

namespace MailPlus\MailPlus\Plugin\Newsletter\Model;

class SubscriberPlugin
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param \Magento\Newsletter\Model\Subscriber $subject
     * @param callable $proceed
     */
    public function aroundSendConfirmationRequestEmail(\Magento\Newsletter\Model\Subscriber $subject, callable $proceed)
    {
        if (!$this->scopeConfig->getValue('mailplus/mailplus/suppress_newsletter_request_email')) {
            $proceed();
        }
    }

    /**
     * @param \Magento\Newsletter\Model\Subscriber $subject
     * @param callable $proceed
     */
    public function aroundSendConfirmationSuccessEmail(\Magento\Newsletter\Model\Subscriber $subject, callable $proceed)
    {
        if (!$this->scopeConfig->getValue('mailplus/mailplus/suppress_newsletter_success_email')) {
            $proceed();
        }
    }

    /**
     * @param \Magento\Newsletter\Model\Subscriber $subject
     * @param callable $proceed
     */
    public function aroundSendUnsubscriptionEmail(\Magento\Newsletter\Model\Subscriber $subject, callable $proceed)
    {
        if (!$this->scopeConfig->getValue('mailplus/mailplus/suppress_newsletter_unsubscribe_email')) {
            $proceed();
        }
    }
}
