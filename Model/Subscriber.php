<?php
namespace MailPlus\MailPlus\Model;

class Subscriber extends \Magento\Newsletter\Model\Subscriber implements \MailPlus\MailPlus\Api\Data\SubscriberInterface
{
    public function setId($value)
    {
        parent::setId($value);
    }

    public function getId()
    {
        return parent::getId();
    }

    public function setEmail($value)
    {
       parent::setEmail($value);
    }

    public function getEmail()
    {
        return parent::getEmail();
    }

    public function setStoreId($value)
    {
        parent::setStoreId($value);
    }

    public function getStoreId()
    {
        return parent::getStoreId();
    }

    public function setWebsiteId($value)
    {
        parent::setWebsiteId($value);
    }

    public function getWebsiteId()
    {
        return parent::getWebsiteId();
    }

    public function setCustomerId($value)
    {
       parent::setCustomerId($value);
    }

    public function getCustomerId()
    {
        return parent::getCustomerId();
    }

    public function setStatus($value)
    {
        parent::setStatus($value);
    }

    public function getStatus()
    {
        return parent::getStatus();
    }

}