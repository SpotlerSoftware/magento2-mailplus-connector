<?php

namespace MailPlus\MailPlus\Api\Data;

interface HealthCheckInterface
{


    /**
     * @api
     * @param string $value
     * @return $this
     */
    public function setMagento2Version($value);

    /**
     * @api
     * @return string
     */
    public function getMagento2Version();


    /**
     * @api
     * @param string $value
     * @return $this
     */
    public function setPluginVersion($value);

    /**
     * @api
     * @return string
     */
    public function getPluginVersion();


    /**
     * @api
     * @param array $features
     * @return $this
     */
    public function setFeatures(array $features);


    /**
     * @api
     * @return array
     */
    public function getFeatures();
}