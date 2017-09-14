<?php

namespace MailPlus\MailPlus\Model;

use Magento\Framework\Model\AbstractModel;
use MailPlus\MailPlus\Api\Data\CatalogRuleUpdatedAtInterface;

class CatalogRuleUpdatedAt extends AbstractModel implements CatalogRuleUpdatedAtInterface
{

    const CATALOG_RULE_ID = "catalog_rule_id";

    const UPDATED_AT = "updated_at";

    public function _construct()
    {
        $this->_init('MailPlus\MailPlus\Model\ResourceModel\CatalogRuleUpdatedAt');
    }

    public function setIsObjectNew($value)
    {
        $this->_isObjectNew = $value;
    }

    public function setCatalogRuleId($catalogRuleId)
    {
        $this->setData(self::CATALOG_RULE_ID, $catalogRuleId);
    }

    /**
     * @return int
     */
    public function getCatalogRuleId()
    {
        return $this->getData(self::CATALOG_RULE_ID);
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }
}