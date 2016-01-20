<?php
 
namespace MailPlus\MailPlus\Model\System\Config\Campaign;
 
use Magento\Framework\Option\ArrayInterface;
 
class Campaign implements ArrayInterface
{
     
    public function toOptionArray()
    {
    	$result = array();
    	$result[''] = __('Disabled');
    	
    	return $result;
    }
}
 