<?php

namespace MailPlus\MailPlus\Service;


use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\ObjectManagerInterface;

class ExtendedOrderSearchResultInterfaceFactory extends \Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory
{
    private $extensionAttributesJoinProcessor;

    public function __construct(ObjectManagerInterface $objectManager, JoinProcessorInterface $extensionAttributesJoinProcessor)
    {
        parent::__construct($objectManager);
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }


    public function create(array $arguments = [])
    {
        $newObject = parent::create($arguments);
        //Add the extension attributes for the results
        $this->extensionAttributesJoinProcessor->process($newObject);
        return $newObject;
    }


}