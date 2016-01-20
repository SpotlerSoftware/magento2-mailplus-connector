<?php
 
namespace MailPlus\MailPlus\Model\System\Config\Sync;
 
use Magento\Framework\Option\ArrayInterface;
 
class ProductSpecifications implements ArrayInterface
{
     
    public function toOptionArray()
    {
    	$result = array();
    	$result[''] = __('None');
    	
    	return $result;
    }
}
 