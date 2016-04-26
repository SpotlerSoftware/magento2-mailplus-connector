<?php
/**
 * Created by IntelliJ IDEA.
 * User: bob
 * Date: 3/30/16
 * Time: 4:09 PM
 */

namespace MailPlus\MailPlus\Model;


use Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\Filesystem;
use Magento\Store\Model\StoreRepository;
use MailPlus\MailPlus\Api\WebsiteProductRepositoryInterface;
use MailPlus\MailPlus\Model\ResourceModel\SiteProduct\SiteProductCollectionFactory;

class WebsiteProductRepository extends ProductRepository implements WebsiteProductRepositoryInterface
{

    private $storeRepository;

    public function __construct(ProductSearchResultsInterfaceFactory $searchResultsFactory,
                                SiteProductCollectionFactory $collectionFactory,
                                SearchCriteriaBuilder $searchCriteriaBuilder,
                                ProductAttributeRepositoryInterface $metadataServiceInterface,
                                JoinProcessorInterface $extensionAttributesJoinProcessor,
                                StoreRepository $storeRepository
    )
    {
        $this->storeRepository = $storeRepository;
        $this->collectionFactory = $collectionFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->metadataService = $metadataServiceInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Get product list
     *
     * @param int $storeId The store ID.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function getStoreProductList($storeId, SearchCriteriaInterface $searchCriteria)
    {
        $store = $this->storeRepository->getById($storeId);
        $this->collectionFactory->setStore($store);
        $result = $this->getList($searchCriteria);
        $this->collectionFactory->setStore(null);
        return $result;
    }


}