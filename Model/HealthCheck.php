<?php

namespace MailPlus\MailPlus\Model;

class HealthCheck implements \MailPlus\MailPlus\Api\Data\HealthCheckInterface
{
    /** @var string $magento2Version */
    private $magento2Version;
    /** @var string $pluginVersion */
    private $pluginVersion;
    /** @var string[] $features */
    private $features;

    /**
     * @param string $value
     * @return $this
     */
    public function setMagento2Version($value)
    {
        $this->magento2Version = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getMagento2Version()
    {
        return $this->magento2Version;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setPluginVersion($value)
    {
        $this->pluginVersion = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getPluginVersion()
    {
        return $this->pluginVersion;
    }

    /**
     * @param string[] $features
     * @return $this
     */
    public function setFeatures(array $features)
    {
        $this->features = $features;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getFeatures()
    {
        return $this->features;
    }


}