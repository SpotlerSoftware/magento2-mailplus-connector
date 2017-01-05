<?php

namespace MailPlus\MailPlus\Model;

use Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory as SearchResultFactory;
use Magento\Sales\Model\OrderRepository;
use Magento\Sales\Model\ResourceModel\Metadata;
use MailPlus\MailPlus\Api\ExtendedOrderRepositoryInterface;
use MailPlus\MailPlus\Service\ExtendedOrderSearchResultInterfaceFactory;

class ExtendedOrderRepository extends OrderRepository implements ExtendedOrderRepositoryInterface
{


    public function __construct(Metadata $metadata, ExtendedOrderSearchResultInterfaceFactory $searchResultFactory)
    {
        //inject the ExtendedOrderSearchResultInterfaceFactory
        parent::__construct($metadata, $searchResultFactory);
    }

}