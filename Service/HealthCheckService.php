<?php

namespace MailPlus\MailPlus\Service;

use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Module\ModuleListInterface;
use MailPlus\MailPlus\Api\Data\HealthCheckInterface;

class HealthCheckService implements HealthCheckServiceInterface
{
    const MODULE_NAME = 'MailPlus_MailPlus';

    const FEATURES = [
        'IMAGE_FALLBACK'
    ];

    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;
    /**
     * @var ModuleListInterface
     */
    private $moduleList;
    /**
     * @var \MailPlus\MailPlus\Api\Data\HealthCheckInterfaceFactory
     */
    private $healthCheckInterfaceFactory;

    public function __construct(ProductMetadataInterface $productMetadata,
                                ModuleListInterface $moduleList,
                                \MailPlus\MailPlus\Api\Data\HealthCheckInterfaceFactory $healthCheckInterfaceFactory)
    {
        $this->productMetadata = $productMetadata;
        $this->moduleList = $moduleList;
        $this->healthCheckInterfaceFactory = $healthCheckInterfaceFactory;
    }

    public function get()
    {
        $magento2Version = $this->productMetadata->getVersion();
        $pluginVersion = $this->moduleList->getOne(self::MODULE_NAME)['setup_version'];

        /** @var HealthCheckInterface $result */
        $result = $this->healthCheckInterfaceFactory->create();
        $result->setMagento2Version($magento2Version)
            ->setPluginVersion($pluginVersion)
            ->setFeatures(self::FEATURES);
        return $result;
    }

}
