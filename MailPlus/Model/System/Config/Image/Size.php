<?php

namespace MailPlus\MailPlus\Model\System\Config\Image;

class Size extends \Magento\Framework\App\Config\Value {
		
	public function beforeSave() {
		$value = $this->getValue ();
		
		//die("<pre>" . print_r($this->getData(), true) . "</pre>");
		
		if (!empty($value ) && !preg_match("/^[0-9]+$/", $value)) {
			$msg = __( 'Invalid value: "' . $this->getFieldConfig()['label']. '" should be numeric');
			throw new \Magento\Framework\Exception\LocalizedException ( $msg );
		}
		return parent::beforeSave();
	}
}