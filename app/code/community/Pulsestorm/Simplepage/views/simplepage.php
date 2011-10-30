<?php
/**
* This is the views file that will contain functions 
* specified in your routes configured in (simplepage.xml)
* 
* @todo Should employ namespacing when php 5.2 fades into the distance. 
*/



/**
* Specified by <view>hello</view>
*
* View function should return a $block, which will be automatically
* appended to the Page Layout's content block
*
* $block is a block object and will have a data member called 
* params which  contains the matched regular expressions from 
* <url_regex/>. The $block's class is controlled by <block_class/>.
* See simplepage.xml for more information.
*
* $layout is a reference to the Magento page layout object, and
* may be used to manipulated the existing layout. (add blocks, 
* unset them, change templates, etc.)
*
* $request and $response are the standard Magento request and response
* objects, in case you want to monkey with them. 
*
* @param Mage_Core_Block_Abstract $block
* @param Mage_Core_Model_Layout   $layout
* @param Mage_Core_Controller_Request_Http $request
* @param Mage_Core_Controller_Response_Http $response
* @return Mage_Core_Block_Template $block
*/
function hello($block, $layout, $request, $response)
{    
    //in this example we're just setting a template on
    //the bock on returning it. Standard Magento theme
    //rules apply for template loading
    $block->setTemplate('pulsestorm_simplepage/example.phtml');  
    //$layout->getBlock('left')->getParentBlock()->unsetChild('left');
    //$layout->getBlock('root')->setTemplate('page/1column.phtml');
    return $block;
}