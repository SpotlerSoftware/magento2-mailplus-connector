<?php
/**
 * Created by IntelliJ IDEA.
 * User: bob
 * Date: 3/31/16
 * Time: 3:09 PM
 */

namespace MailPlus\MailPlus\Model\ResourceModel\SiteProduct;


use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class SiteProductCollectionFactory extends CollectionFactory
{
    private $collectionFactory;
    private $store;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * @param \Magento\Store\Api\Data\StoreInterface $store
     */
    public function setStore($store)
    {
        $this->store = $store;
    }

    public function create(array $data = Array())
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create($data);
        if (!empty($this->store)) {
            $collection->setStore($this->store);
        }
        return $collection;
    }

}