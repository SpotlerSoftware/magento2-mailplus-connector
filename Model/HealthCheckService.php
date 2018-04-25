<?php

namespace MailPlus\MailPlus\Model;

use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Module\ModuleListInterface;
use MailPlus\MailPlus\Api\Data\HealthCheckInterface;
use MailPlus\MailPlus\Api\HealthCheckServiceInterface;

class HealthCheckService implements HealthCheckServiceInterface
{
    const MODULE_NAME = 'MailPlus_MailPlus';
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
            ->setFeatures([]);
        return $result;
    }

}
