<?php

namespace MailPlus\MailPlus\Plugin;

use DateTime;
use Magento\CatalogRule\Api\CatalogRuleRepositoryInterface;
use MailPlus\MailPlus\Model\CatalogRuleUpdatedAt;
use MailPlus\MailPlus\Model\CatalogRuleUpdatedAtFactory;

class CatalogRuleRepositoryPlugin
{
    private $_catalogRuleUpdatedAtFactory;


    /**
     * CatalogRuleRepositoryPlugin constructor.
     */
    public function __construct(CatalogRuleUpdatedAtFactory $catalogRuleUpdatedAtFactory)
    {
        $this->_catalogRuleUpdatedAtFactory = $catalogRuleUpdatedAtFactory;
    }


    /**
     * @param CatalogRuleRepositoryInterface $subject
     * @param \Magento\CatalogRule\Model\Rule $result
     * @return \Magento\CatalogRule\Api\Data\RuleInterface
     */
    public function afterSave(CatalogRuleRepositoryInterface $subject, $result)
    {
        $dateTime = (new DateTime())->format('c');

        /** @var CatalogRuleUpdatedAt $ruleUpdatedAt */
        $ruleUpdatedAt = $this->_catalogRuleUpdatedAtFactory->create();
        $ruleUpdatedAt->load($result->getRuleId());
        if (empty($ruleUpdatedAt->getUpdatedAt())) {
            $ruleUpdatedAt->setUpdatedAt($dateTime);
            $ruleUpdatedAt->setCatalogRuleId($result->getRuleId());
            $ruleUpdatedAt->setIsObjectNew(true);
        } else {
            $ruleUpdatedAt->setUpdatedAt($dateTime);
        }
        $ruleUpdatedAt->save();

        return $result;
    }
}