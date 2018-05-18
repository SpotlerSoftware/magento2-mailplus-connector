<?php

namespace MailPlus\MailPlus\Service;

interface HealthCheckServiceInterface
{
    /**
     * @return \MailPlus\MailPlus\Api\Data\HealthCheckInterface
     */
    public function get();
}