<?php
 
namespace MailPlus\MailPlus\Model\System\Config\Image;
 
use Magento\Framework\Option\ArrayInterface;
 
class Format implements ArrayInterface
{
    const FIXED    = 1;
    const VARIABLE = 0;
 
    public function toOptionArray()
    {   	
        return [
            self::FIXED => __('Fixed width and height'),
            self::VARIABLE => __('Variable width and height')
        ];
    }
}
 