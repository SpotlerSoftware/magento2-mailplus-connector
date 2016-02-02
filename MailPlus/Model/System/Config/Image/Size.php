<?php

namespace MailPlus\MailPlus\Model\System\Config\Image;

use Magento\Framework\App\Config\Value;
use Magento\Framework\Exception\LocalizedException;

class Size extends Value {
		
	public function beforeSave() {
		$value = $this->getValue ();
		
		//die("<pre>" . print_r($this->getData(), true) . "</pre>");
		
		if (!empty($value ) && !preg_match("/^[0-9]+$/", $value)) {
			$msg = __( 'Invalid value: "' . $this->getFieldConfig()['label']. '" should be numeric');
			throw new LocalizedException ( $msg );
		}
		return parent::beforeSave();
	}
}