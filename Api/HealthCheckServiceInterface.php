<?php

namespace MailPlus\MailPlus\Api;

interface HealthCheckServiceInterface
{
    /**
     * @return \MailPlus\MailPlus\Api\Data\HealthCheckInterface
     */
    public function get();
}