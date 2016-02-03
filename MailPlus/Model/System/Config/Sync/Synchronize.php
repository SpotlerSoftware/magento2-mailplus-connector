<?php
 
namespace MailPlus\MailPlus\Model\System\Config\Sync;
 
use Magento\Framework\Option\ArrayInterface;
 
class Synchronize implements ArrayInterface
{
    const ALL      = "all";
    const PRODUCTS = "products";
 
    public function toOptionArray()
    {   	
        return [
            self::ALL => __('Contacts and Products'),
            self::PRODUCTS => __('Only producs')
        ];
    }
}
 