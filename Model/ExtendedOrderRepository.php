<?php

namespace MailPlus\MailPlus\Model;


use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory as SearchResultFactory;
use Magento\Sales\Model\ResourceModel\Metadata;
use MailPlus\MailPlus\Api\ExtendedOrderRepositoryInterface;

class ExtendedOrderRepository extends \Magento\Sales\Model\OrderRepository implements ExtendedOrderRepositoryInterface
{


    private $extensionAttributesJoinProcessor;

    public function __construct(Metadata $metadata, SearchResultFactory $searchResultFactory, JoinProcessorInterface $extensionAttributesJoinProcessor)
    {
        parent::__construct($metadata, $searchResultFactory);
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }

    /**
     * Find entities by criteria
     *
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return OrderInterface[]
     */
    public function getList(SearchCriteria $searchCriteria)
    {
        /** @var \Magento\Sales\Api\Data\OrderSearchResultInterface $searchResult */
        $searchResult = parent::getList($searchCriteria);
        //Added extensionAttributes
        $this->extensionAttributesJoinProcessor->process($searchResult);
        //Reload results, not great performance but otherwise we'd have to copy a bunch of code from \Magento\Sales\Model\OrderRepository.
        // or write some very complicated injections to have \Magento\Sales\Model\OrderRepository call the entensionAttributeJoiner.
        $searchResult->clear();
        $searchResult->getItems();
        return $searchResult;
    }

}