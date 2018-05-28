<?php

namespace MailPlus\MailPlus\Api;

interface HealthCheckRepositoryInterface
{
    /**
     * @return \MailPlus\MailPlus\Api\Data\HealthCheckInterface
     */
    public function get();
}