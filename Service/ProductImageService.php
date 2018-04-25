<?php

namespace MailPlus\MailPlus\Service;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductImageService implements ProductImageServiceInterface
{
    /** @var $productRepository ProductRepositoryInterface */
    private $productRepository;

    /** @var AbstractType $_configurableProductHelper */
    private $_configurableProductHelper;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_configurableProductHelper = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable');
    }

    /**
     * @param int $id
     * @param int $storeId
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id, int $storeId)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->productRepository->getById($id, false, $storeId);
        $imageUrl = $this->getImageUrl($product);

        if (!$imageUrl) {
            $parentProductIds = $this->_configurableProductHelper->getParentIdsByChild($product->getId());
            foreach ($parentProductIds as $parentProductId) {
                /** @var \Magento\Catalog\Model\Product $parentProduct */
                $parentProduct = $this->productRepository->getById($parentProductId);
                $imageUrl = $this->getImageUrl($parentProduct);
                if ($imageUrl) {
                    break;
                }
            }
        }

        if (!$imageUrl) {
            throw new NoSuchEntityException(__("Unable to find image for product id " . $id));
        }
        header('Location: ' . $imageUrl);
        die();
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return bool|string
     */
    private function getImageUrl($product)
    {
        $dataObject = $product->getMediaGalleryImages()->getFirstItem();
        return $dataObject ? $dataObject->getData('url') : '';
    }
}
