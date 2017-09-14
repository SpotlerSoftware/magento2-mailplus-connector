<?php

namespace MailPlus\MailPlus\Api\Data;


interface CatalogRuleUpdatedAtInterface
{
    public function setCatalogRuleId($catalogRuleId);

    /**
     * @return int
     */
    public function getCatalogRuleId();

    public function setUpdatedAt($updatedAt);

    /**
     * @return string
     */
    public function getUpdatedAt();
}