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
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Store\Model\StoreRepository;
use MailPlus\MailPlus\Api\WebsiteProductRepositoryInterface;

class WebsiteProductRepository implements WebsiteProductRepositoryInterface
{


    private $_searchResultsFactory;
    private $collectionFactory;
    private $extensionAttributesJoinProcessor;
    private $metadataService;
    private $searchCriteriaBuilder;
    private $storeRepository;
    private $searchResultsFactory;

    public function __construct(SearchResultsFactory $searchResultsFactory,
                                CollectionFactory $collectionFactory,
                                JoinProcessorInterface $extensionAttributesJoinProcessor,
                                ProductAttributeRepositoryInterface $metadataService,
                                SearchCriteriaBuilder $searchCriteriaBuilder,
                                StoreRepository $storeRepository,
                                ProductSearchResultsInterfaceFactory $searchResultsFactory
    )
    {
        $this->_searchResultsFactory = $searchResultsFactory;
        $this->collectionFactory = $collectionFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->metadataService = $metadataService;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeRepository = $storeRepository;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Get product list
     *
     * @param int $storeId The store ID.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function getList($storeId, SearchCriteriaInterface $searchCriteria)
    {
        $store = $this->storeRepository->getById($storeId);

        //TODO: Copy pasted from ProductRepository.. might be a better way
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->extensionAttributesJoinProcessor->process($collection);
        $collection->setStore($store);

        foreach ($this->metadataService->getList($this->searchCriteriaBuilder->create())->getItems() as $metadata) {
            $collection->addAttributeToSelect($metadata->getAttributeCode());
        }
        $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
        $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');

        //Add filters from root filter group to the collection
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        /** @var SortOrder $sortOrder */
        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $field = $sortOrder->getField();
            $collection->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
            );
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->load();

        /** @var \Magento\Framework\Api\SearchResults $searchResult */
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup $filterGroup
     * @param Collection $collection
     * @return void
     */
    protected function addFilterGroupToCollection(
        FilterGroup $filterGroup,
        Collection $collection
    )
    {
        //TODO: Copy pasted from ProductRepository.. might be a better way
        $fields = [];
        $categoryFilter = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $conditionType = $filter->getConditionType() ? $filter->getConditionType() : 'eq';

            if ($filter->getField() == 'category_id') {
                $categoryFilter[$conditionType][] = $filter->getValue();
                continue;
            }
            $fields[] = ['attribute' => $filter->getField(), $conditionType => $filter->getValue()];
        }

        if ($categoryFilter) {
            $collection->addCategoriesFilter($categoryFilter);
        }

        if ($fields) {
            $collection->addFieldToFilter($fields);
        }
    }
}