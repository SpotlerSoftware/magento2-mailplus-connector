<?php

namespace MailPlus\MailPlus\Block\System\Config\Form\Fieldset;

class Mapping extends \Magento\Config\Block\System\Config\Form\FieldSet {
	
	/**
	 * @var \Magento\Framework\DataObject
	 */
	protected $_dummyElement;
	
	/**
	 * @var \Magento\Config\Block\System\Config\Form\Field
	 */
	protected $_fieldRenderer;
	
	/**
	 * @var array
	 */
	protected $_values;
	
	/**
	 *
	 * @param \Magento\Backend\Block\Context $context        	
	 * @param \Magento\Backend\Model\Auth\Session $authSession        	
	 * @param \Magento\Framework\View\Helper\Js $jsHelper        	
	 * @param \Magento\Framework\Module\ModuleListInterface $moduleList        	
	 * @param array $data        	
	 */
	public function __construct(\Magento\Backend\Block\Context $context,
			\Magento\Backend\Model\Auth\Session $authSession,
			\Magento\Framework\View\Helper\Js $jsHelper,
			array $data = []) {
		parent::__construct ( $context, $authSession, $jsHelper, $data );
	}
	
	/**
	 * @return \Magento\Framework\DataObject
	 */
	protected function _getDummyElement() {
		if (empty($this->_dummyElement)) {
			$this->_dummyElement = new \Magento\Framework\DataObject(['showInDefault' => 1, 'showInWebsite' => 1]);
		}
		return $this->_dummyElement;
	}
	
	
	/**
	 * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
	 * @return string
	 */
	public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element) {
		$html = $this->_getHeaderHtml($element);
	
		$magentoFields = ['Test 1', 'Test2'];
	
		foreach ($magentoFields as $field) {
			$html .= $this->_getFieldHtml($element, $field);
		}
		$html .= $this->_getFooterHtml($element);
	
		return $html;
	}
	
	/**
	 * @return \Magento\Config\Block\System\Config\Form\Field
	 */
	protected function _getFieldRenderer() {
		if (empty($this->_fieldRenderer)) {
			$this->_fieldRenderer = $this->_layout->getBlockSingleton(
					'Magento\Config\Block\System\Config\Form\Field'
					);
		}
		return $this->_fieldRenderer;
	}
	
	
	/**
	 * @return array
	 */
	protected function _getValues()
	{
		if (empty($this->_values)) {
			$this->_values = [
					['label' => __('Enable'), 'value' => 0],
					['label' => __('Disable'), 'value' => 1],
					['label' => __('Nope'), 'value' => 3],
			];
		}
		return $this->_values;
	}
	
	
	/**
	 * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
	 * @param string $moduleName
	 * @return mixed
	 */
	protected function _getFieldHtml($fieldset, $moduleName) {
	
		$configData = $this->getConfigData();
		$path = 'mapping/site_mapping/' . $moduleName;
		
		//TODO: move as property of form
		if (isset($configData[$path])) {
			$data = $configData[$path];
		} else {
			$data = 3;
		}
				
		$element = $this->_getDummyElement();
	
		$field = $fieldset->addField(
				$moduleName,
				'select',
				[
					'name' => 'groups[site_mapping][fields][' . $moduleName . '][value]',
					'label' => $moduleName,
					'value' => $data,
					'values' => $this->_getValues(),
					'inherit' => false,
					'can_use_default_value' => false,
					'can_use_website_value' => false
				]
			)->setRenderer($this->_getFieldRenderer());
	
		return $field->toHtml();
	}
	
	
	
}