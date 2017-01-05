<?php
/**
 * Created by IntelliJ IDEA.
 * User: bob
 * Date: 13-12-16
 * Time: 10:15
 */

namespace MailPlus\MailPlus\Observer;


use Magento\Customer\Model\Session;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;


class ControllerPreDispatchObserver implements ObserverInterface
{
    private $sessionManager;
    private $request;


    public function __construct(Session $customerSession, Http $request)
    {
        $this->sessionManager = $customerSession;
        $this->request = $request;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $mpId = $this->request->getParam("mpId");
        if (isset($mpId)) {
            //Sanitize
            $mpId = preg_replace('/[^-a-zA-Z0-9_]/', '', $mpId);
            $this->sessionManager->setMpId($mpId);
        }
    }
}